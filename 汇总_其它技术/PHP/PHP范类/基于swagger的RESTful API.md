基于swagger的RESTful API

摘要： 前言 RESTful架构，是目前最流行的一种互联网软件架构。它结构清晰、符合标准、易于理解、扩展方便，所以正得到越来越多网站的采用。后端通过提供一套标准的RESTful API，让网站，移动端和第三方系统都可以基于API进行数据交互和对接，极大的提高系统的开发效率，也使得前后端分离架构成为可能。 …

---

![20160701165715817](https://gitee.com/hxc8/images8/raw/master/img/202407191111248.jpg)

- 基于swagger的RESTful API

- 前言

- 以下为自己整理的swagger UI 使用步骤

- 首先用git获取swagger UI 代码

- 然后将代码中 dist内容复制到项目中

- 以下为个人习惯配置

- 修改 indexhtml

- 根据接口修改 配置的swaggerjson文件

前言

RESTful架构，是目前最流行的一种互联网软件架构。它结构清晰、符合标准、易于理解、扩展方便，所以正得到越来越多网站的采用。后端通过提供一套标准的RESTful API，让网站，移动端和第三方系统都可以基于API进行数据交互和对接，极大的提高系统的开发效率，也使得前后端分离架构成为可能。 

因此，不同的测试，开发团队（前端，移动端，第三方接入者等）都需要围绕API进行开发工作，API的规范和文档对于团队开发，测试变得越来越重要。除了一份标准的文档，我们还希望API能够在线测试使用，从而有更直观的API使用体验，降低API的学习成本。这些对于团队的开发协作都会事半功倍。 

本文将介绍一些API文档和开发测试方面的一些实践，使用typeson，docson，swagger-ui等开源工具，建立一个API的集设计，实现，测试，文档的一体化可视平台，让API的开发和使用更加高效。

- Swagge 

Swagger是一种和语言无关的规范和框架，用于定义服务接口，主要用于描述RESTful的API。它专注于为API创建优秀的文档和客户端库。支持Swagger的API可以为API方法生成交互式的文档，让用户可以通过以可视化的方式试验，查看请求和响应、头文件和返回代码，从而发现API的功能。它本身就非常强大，但是Swagger框架还支持为多种流行的语言——包括JavaScript、Python、Ruby、Java、Scala等等——生成客户端代码。 

swagger [github地址][(2]

- Swagger-UI 

为基于Swagger规范的API生成基于基于纯粹HTML,javascript,css的在线可视化文档，同时也能成为API的在线测试工具。 

swagger UI github地址

---

以下为自己整理的swagger UI 使用步骤

1. 首先用git获取swagger UI 代码

2. 然后将代码中 dist内容复制到项目中

以下为个人习惯配置

- 在入口处添加swagger 和 api-docs 两个文件

- 将 dist 中内容 复制到 swagger中， 将 dist/json/swagger.json 复制到 api-docs中

3. 修改 index.html

修改代码

修改代码

  <!-- Some basic translations -->
  <!-- <script src='lang/translator.js' type='text/javascript'></script> -->
  <!-- <script src='lang/ru.js' type='text/javascript'></script> -->
  <!-- <script src='lang/en.js' type='text/javascript'></script> -->

为

   <script src='lang/translator.js' type='text/javascript'></script>
   <script src='lang/zh-CN.js' type='text/javascript'></script>

目的是支持中文显示



修改代码

window.swaggerUi = new SwaggerUi({
        url: url,
        dom_id: "swagger-ui-container",
        supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
        onComplete: function(swaggerApi, swaggerUi){
          if(typeof initOAuth == "function") {
            initOAuth({
              clientId: "your-client-id",
              clientSecret: "your-client-secret-if-required",
              realm: "your-realms",
              appName: "your-app-name",
              scopeSeparator: ",",
              additionalQueryStringParams: {}
            });
          }

          if(window.SwaggerTranslator) {
            window.SwaggerTranslator.translate();
          }
        },

为

window.swaggerUi = new SwaggerUi({
        url: '/api-docs/swagger.json',
        dom_id: "swagger-ui-container",
        supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
        onComplete: function(swaggerApi, swaggerUi){
          if(typeof initOAuth == "function") {
            /*initOAuth({
              clientId: "your-client-id",
              clientSecret: "your-client-secret-if-required",
              realm: "your-realms",
              appName: "your-app-name",
              scopeSeparator: ",",
              additionalQueryStringParams: {}
            });*/
          }

          if(window.SwaggerTranslator) {
            window.SwaggerTranslator.translate();
          }
        },

修改url 指向swagger.json 

删除认证

4. 根据接口修改 配置的swagger.json文件

"swagger": "2.0",
    "info": {
    //接口文档描述
        "description": "This is a sample server Petstore server.  ",
        //接口文档版本号
        "version": "1.0.0",
        //标题
        "title": "Swagger Petstore",
        //服务条款
        "termsOfService": "http://swagger.io/terms/",
        //联系作者
        "contact": {
            "email": "apiteam@swagger.io"
        },
        //许可
        "license": {
            "name": "Apache 2.0",
            "url": "http://www.apache.org/licenses/LICENSE-2.0.html"
        }
    },
    //接口地址
    "host": "petstore.swagger.io",
    //基础地址（模块）
    "basePath": "/v2",
    //标签 为列出的几大接口模块
    "tags": [
        {
            //名称
            "name": "pet",
            //描述
            "description": "Everything about your Pets",
            //外部文档
            "externalDocs": {
                "description": "Find out more",
                "url": "http://swagger.io"
            }
        },
        {
            "name": "store",
            "description": "Access to Petstore orders"
        }
    ],

以上主要修改描述，标题，接口地址，基础地址 和标签

"/pet": {
            //请求方式  支持 post get put delete patch
            "post": {
                //对应标签 可配置多个
                "tags": [
                    "pet"
                ],
                //接口说明
                "summary": "Add a new pet to the store",
                //备注
                "description": "",
                //操作ID 貌似没啥用
                "operationId": "addPet",
                //提交的数据类型
                "consumes": [
                    "application/json",
                    "application/xml"
                ],
                //返回的数据类型
                "produces": [
                    "application/xml",
                    "application/json"
                ],
                //提交的参数(数据)
                "parameters": [
                    {
                        //常用 query formData body header path
                        "in": "body",
                        //参数名称
                        "name": "body",
                        //描述
                        "description": "Pet object that needs to be added to the store",
                        //是否必须
                        "required": true,

                        "schema": {
                            "$ref": "#/definitions/Pet"
                        }
                    }

                ],
                //响应
                "responses": {
                    "405": {
                        //描述
                        "description": "Invalid input"
                    }
                },
                //是否弃用
              "deprecated": true
            },

"definitions": {
        "Pet": {
            "type": "object",
            //必须的
            "required": [
                "name",
                "photoUrls"
            ],
            "properties": {
                "id": {
                    //数据类型 常用 integer string array boolean object file等
                    "type": "integer",
                    "format": "int64"
                },
                "category": {
                    "$ref": "#/definitions/Category"
                },
                "name": {
                    "type": "string",
                    "example": "doggie"
                },
                "photoUrls": {
                    "type": "array",
                    "xml": {
                        "name": "photoUrl",
                        "wrapped": true
                    },
                    "items": {
                        "type": "string"
                    }
                },
                "tags": {
                    "type": "array",
                    "xml": {
                        "name": "tag",
                        "wrapped": true
                    },
                    "items": {
                        "$ref": "#/definitions/Tag"
                    }
                },
                "status": {
                    "type": "string",
                    "description": "pet status in the store",
                    "enum": [
                        "available",
                        "pending",
                        "sold"
                    ]
                }
            },
            "xml": {
                "name": "Pet"
            }
        },
        "ApiResponse": {
            "type": "object",
            "properties": {
                "code": {
                    "type": "integer",
                    "format": "int32"
                },
                "type": {
                    "type": "string"
                },
                "message": {
                    "type": "string"
                }
            }
        }
    },









