<?php

创建ACL
$acl = new \Phalcon\Acl\Adapter\Memory();
// Default action is deny access
$acl->setDefaultAction(Phalcon\Acl::DENY);
--------------

添加角色到ACL
角色是访问权限列表的对象.
// Create some roles
$roleAdmins = new \Phalcon\Acl\Role("Administrators", "Super-User role");
$roleGuests = new \Phalcon\Acl\Role("Guests");
// Add "Guests" role to acl
$acl->addRole($roleGuests);
// Add "Designers" role to acl without a Phalcon\Acl\Role
$acl->addRole("Designers");
As you can see, roles are defined directly without using an instance.
----------

添加资源
资源是访问控制的对象
// Define the "Customers" resource
$customersResource = new \Phalcon\Acl\Resource("Customers");
// Add "customers" resource with a couple of operations
$acl->addResource($customersResource, "search");
$acl->addResource($customersResource, array("create", "update"));
------------

定义访问控制
是时候来定义ACL即哪些角色可以访问哪些资源了
// Set access level for roles into resources
$acl->allow("Guests", "Customers", "search");
$acl->allow("Guests", "Customers", "create");
$acl->deny("Guests", "Customers", "update");
-----------

查询ACL
// Check whether role has access to the operations
$acl->isAllowed("Guests", "Customers", "edit");   //Returns 0
$acl->isAllowed("Guests", "Customers", "search"); //Returns 1
$acl->isAllowed("Guests", "Customers", "create"); //Returns 1
-----------

角色继承
// Create some roles
$roleAdmins = new \Phalcon\Acl\Role("Administrators", "Super-User role");
$roleGuests = new \Phalcon\Acl\Role("Guests");
// Add "Guests" role to acl
$acl->addRole($roleGuests);
// Add "Administrators" role inheriting from "Guests" its accesses
$acl->addRole($roleAdmins, $roleGuests);
-----------

序列化ACL列表
为了提高性能可以把ACL序列化后存储在APC,文本,DB,缓存,会话等.
//Check whether acl data already exist
if (!file_exists("app/security/acl.data")) {
    $acl = new \Phalcon\Acl\Adapter\Memory();
    //... Define roles, resources, access, etc
    // Store serialized list into plain file
    file_put_contents("app/security/acl.data", serialize($acl));
} else {
     //Restore acl object from serialized file
     $acl = unserialize(file_get_contents("app/security/acl.data"));
}
// Use acl list as needed
if ($acl->isAllowed("Guests", "Customers", "edit")) {
    echo "Access granted!";
} else {
    echo "Access denied :(";
}
--------------

ACL事件
Event Name				Triggered													Can stop operation?
beforeCheckAccess		Triggered before checking if a role/resource has access		Yes
afterCheckAccess		Triggered after checking if a role/resource has access		No

//Create an event manager
$eventsManager = new Phalcon\Events\Manager();
//Attach a listener for type "acl"
$eventsManager->attach("acl", function($event, $acl) {
    if ($event->getType() == 'beforeCheckAccess') {
         echo   $acl->getActiveRole(),
                $acl->getActiveResource(),
                $acl->getActiveAccess();
    }
});
$acl = new \Phalcon\Acl\Adapter\Memory();
//Setup the $acl
//...
//Bind the eventsManager to the acl component
$acl->setEventsManager($eventManagers);

