1、预载入条件限制

$users = User::with(['posts' => function($query)

{

    $query->where('title', 'like', '%first%');



}])->get();

或者

在posts对象中添加where条件

public function posts()

{

    return $this->hasOne(‘PostsModel', 'Fcompany_id', 'Fhunter_company_id')->where('Fcom_admin', 1);

}

//        附加一个关联模型

//        $tags = [

//            new JobTagModel([

//                'Ftag' => 'tag23',

//            ]),

//            new JobTagModel([

//                'Ftag' => 'tag21',

//            ]),

//            new JobTagModel([

//                'Ftag' => 'tag22',

//            ])

//        ];

//        $job = JobIndexModel::find(21161);

//        $tag = $job->tags()->saveMany($tags);

//        GlobalEnv::showSqlLog();

//        die;

//        return $tag;



//        从属关联模型 ( Belongs To )

//        $job = JobIndexModel::find(9);

//        $tag = new JobTagModel();

//        $tag->job()->associate($job);

//        $tag->save();

//        GlobalEnv::showSqlLog();

//        die;



//        //新增多对多关联模型 ( Many To Many )

//        $job = JobIndexModel::find(9);

//        $job->candidates()->detach([12, 13, 14]);

//        $job->candidates()->attach([12 => ['Fpush_reason' => 111111], 13=> ['Fpush_reason' => ''], 14=> ['Fpush_reason' => 333333]]);

//        GlobalEnv::showSqlLog();

//        die;



        //使用 Sync 方法同时附加一个以上多对多关联   下面的代码  中枢表里面只剩下1,2,3,4,5,6   比如之前有7 也会被删掉

//        $job = JobIndexModel::find(9);

//        $job->candidates()->sync([1 => ['Fpush_reason' => 12321321], 2, 3, 4, 5, 6]);

//        GlobalEnv::showSqlLog();

//        die;



//        在建立新模型数据的同时附加关联

//        $job = new JobIndexModel([

//            'Fstatus' => 1,

//            'Fpm_id' => 1,

//            'Fhr_id' => 1,

//            'Fcom_id' => 1

//        ]);

//        CandidateModel::find(1)->jobs()->save($job, ['Fpush_reason' => 111111111]);

//        GlobalEnv::showSqlLog();

//        die;





//        更新上层时间戳

//        $tag = JobTagModel::find(233);

//        $tag->Ftag = 'aaaaaa';

//        $tag->save();

//        GlobalEnv::showSqlLog();

//        die;



//        使用枢纽表

//        $job = JobIndexModel::find(44277);

//        foreach ($job->candidates as $candidate) {

//            print_r(json_encode($candidate->pivot));

//            die;

//        }

//        GlobalEnv::showSqlLog();

//        die;



//        更新枢纽表的数据

//        $job = JobIndexModel::find(44277);

//        $job->candidates()->updateExistingPivot(65622, ['Fcandidate_email' => '111111@qq.com']);

//        GlobalEnv::showSqlLog();

//        die;



//      集合过滤

//        $jobs = JobIndexModel::whereFcomId(8)->whereFstatus(['opt' => '<', 'val' => '3'])->get();

//        $jobs = $jobs->filter(function ($job) {

//            return $job->Fstatus == 1;

//        });

////        注意： 如果要在过滤集合之后转成 JSON，转换之前先调用 values 方法重设数组的键值

//        print_r(json_encode($jobs->values()));

//        die;