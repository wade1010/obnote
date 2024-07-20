func (api objectAPIHandlers) PutObjectRetentionHandler(w http.ResponseWriter, r *http.Request) {

if rcfg, _ := globalBucketObjectLockSys.Get(bucket); !rcfg.LockEnabled {

   writeErrorResponse(ctx, w, errorCodes.ToAPIErr(ErrInvalidBucketObjectLockConfiguration), r.URL)

   return

}

objRetention, err := objectlock.ParseObjectRetention(r.Body)

objInfo, s3Err := enforceRetentionBypassForPut(ctx, r, bucket, object, getObjectInfo, objRetention, cred, owner)



}





// enforceRetentionBypassForPut enforces whether an existing object under governance can be overwritten

// with governance bypass headers set in the request.

// Objects under site wide WORM cannot be overwritten.

// For objects in "Governance" mode, overwrite is allowed if a) object retention date is past OR

// governance bypass headers are set and user has governance bypass permissions.

// Objects in compliance mode can be overwritten only if retention date is being extended. No mode change is permitted.

func enforceRetentionBypassForPut(ctx context.Context, r *http.Request, bucket, object string, getObjectInfoFn GetObjectInfoFn, objRetention *objectlock.ObjectRetention, cred auth.Credentials, owner bool) (ObjectInfo, APIErrorCode) {

	byPassSet := objectlock.IsObjectLockGovernanceBypassSet(r.Header)

	opts, err := getOpts(ctx, r, bucket, object)

	if err != nil {

		return ObjectInfo{}, toAPIErrorCode(ctx, err)

	}



	oi, err := getObjectInfoFn(ctx, bucket, object, opts)

	if err != nil {

		return oi, toAPIErrorCode(ctx, err)

	}



	t, err := objectlock.UTCNowNTP()

	if err != nil {

		logger.LogIf(ctx, err)

		return oi, ErrObjectLocked

	}



	// Pass in relative days from current time, to additionally to verify "object-lock-remaining-retention-days" policy if any.

	days := int(math.Ceil(math.Abs(objRetention.RetainUntilDate.Sub(t).Hours()) / 24))



	ret := objectlock.GetObjectRetentionMeta(oi.UserDefined)

	if ret.Mode.Valid() {

		// Retention has expired you may change whatever you like.

		if ret.RetainUntilDate.Before(t) {

			perm := isPutRetentionAllowed(bucket, object,

				days, objRetention.RetainUntilDate.Time,

				objRetention.Mode, byPassSet, r, cred,

				owner)

			return oi, perm

		}



		switch ret.Mode {

		case objectlock.RetGovernance:

			govPerm := isPutRetentionAllowed(bucket, object, days,

				objRetention.RetainUntilDate.Time, objRetention.Mode,

				byPassSet, r, cred, owner)

			// Governance mode retention period cannot be shortened, if x-amz-bypass-governance is not set.

			if !byPassSet {

				if objRetention.Mode != objectlock.RetGovernance || objRetention.RetainUntilDate.Before((ret.RetainUntilDate.Time)) {

					return oi, ErrObjectLocked

				}

			}

			return oi, govPerm

		case objectlock.RetCompliance:

			// Compliance retention mode cannot be changed or shortened.

			// https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock-overview.html#object-lock-retention-modes

			if objRetention.Mode != objectlock.RetCompliance || objRetention.RetainUntilDate.Before((ret.RetainUntilDate.Time)) {

				return oi, ErrObjectLocked

			}

			compliancePerm := isPutRetentionAllowed(bucket, object,

				days, objRetention.RetainUntilDate.Time, objRetention.Mode,

				false, r, cred, owner)

			return oi, compliancePerm

		}

		return oi, ErrNone

	} // No pre-existing retention metadata present.



	perm := isPutRetentionAllowed(bucket, object,

		days, objRetention.RetainUntilDate.Time,

		objRetention.Mode, byPassSet, r, cred, owner)

	return oi, perm

}





