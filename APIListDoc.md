# MlTree Forum API文档

文档用于对API的详细描述以及调用方法的归纳。以便于各开发者能够依据API完成自己的二次拓展。

文档主要分x个部分进行

## APIList查询

[TOC]

## app\forum\controller\Api

#### Index

**描述**

 - 输出APIlist的各个URL

**请求URL**

 - `/Api/Index` 或 `/Api`

**请求方式**

 - `GET`

**请求参数**

无需参数

#### getTopicList


**描述**

 - 获取帖子列表

**请求URL**

 - `Api/getTopicList`

**请求方式**

 - `POST`

**请求参数**

| 参数名 | 类型 | 必选 | 默认值 | 说明 |
| ------|-------|-----|-----|-----|
| page | Int | 否 | 1 | 页码 |
| type | String | 否 | common | 输出的类型，common或essence|

**返回示例**

```json
{
    "code": 0,
    "data": {
        "data": [
            {
                "tid": 3948,
                "fid": 2,
                "uid": 1,
                "sign": null,
                "userip": "",
                "subject": "2535",
                "create_time": "2019-01-13 15:03:33",
                "update_time": "2019-01-13 15:03:33",
                "content": "2535",
                "views": 0,
                "comment": 0,
                "images": 0,
                "closed": false,
                "tops": 0,
                "essence": 0,
                "likes": 0,
                "time_format": "2019-01-13 15:03",
                "userData": {
                    "username": "十载北林",
                    "avatar": "/avatar/20180617/822a6cff8d502848aa041369e5f58150.png"
                },
                "forumName": "水漫金山",
                "Badge": ""
            }
        ],
        "pages": 159
    },
    "time": 1548867470
}
```

 **返回参数说明** 

|参数名|类型|说明|
|-----|-----|----- |
|pages | Int | 页码总数 |

 **备注** 

- 更多返回错误代码请看首页的错误代码描述

#### getTopicData

**描述**

 - 获取帖子内容

**请求URL**

 - `Api/getTopicData/tid/{$tid}`

**请求方式**

 - `GET`

**请求参数**

| 参数名 | 类型 | 必选 | 默认值 | 说明 |
| ------|-------|-----|-----|-----|
| tid | Int | 是 | 1 | 页码 |

**返回示例**

```json
{
    "code": 0,
    "data": {
        "tid": 3972,
        "fid": 2,
        "uid": 9,
        "sign": "PjAixwhX57b2NlLDQsOeHcm8dTFJMU",
        "user_ip": "127.0.0.1",
        "subject": "123",
        "create_time": "2019-01-22 21:08:02",
        "update_time": "2019-01-31 11:21:05",
        "content": "123",
        "views": 91,
        "comment": 2,
        "closed": false,
        "tops": 0,
        "essence": 0,
        "likes": 0,
        "time_format": "2019-01-22 21:08",
        "userData": {
            "username": "Kingsr",
            "avatar": "\\static\\images%user_defaule.png"
        },
        "forumName": "水漫金山",
        "Badge": ""
    },
    "time": 1548904865
}
```

 **返回参数说明** 

|参数名|类型|说明|
| ----- |:-----:| ----- |
| tid | Int | 帖子ID |
| fid | Int | 所属板块ID |
| uid | Int | 发帖人ID |
| sign | String | 帖子唯一用于附件的标识 |
| user_ip | String | 发帖人Ip |
| subject | String |  帖子标题 |
| create_time | String | 发帖时间 |
| update_time | String | 更新时间 |
| content | String | 帖子内容 |
| views | Int | 浏览次数 |
| comment | Int | 评论总数 |
| closed | Bool | 是否关闭 |
| tops | Int | 置顶状态 |
| essence | Int | 精华状态 |
| likes | Int | 点赞总数 |
| time_format | String | 格式化后的发帖时间 |
| forumName | String | 所属板块名 |
| Badge | String | 置顶精华状态徽章 |

 **备注** 

- tops、essence值为0=否、1=是
- 更多返回错误代码请看首页的错误代码描述

#### getCommentList

**描述**

 - 获取指定帖子的评论列表

**请求URL**

 - `Api\getCommentList\tid\{$tid}\page\{$page}`

**请求方式**

 - `GET`

**请求参数**

| 参数名 | 类型 | 必选 | 默认值 | 说明 |
| ------|-------|-----|-----|-----|
| tid | Int | 是 | 0 | 帖子ID |
| page | Int | 是 | 1 | 页码 |

**返回示例**

```json
{
    "code": 0,
    "data": {
        "data": [
            {
                "cid": 1506,
                "tid": 3972,
                "uid": 9,
                "content": "123",
                "create_time": "2019-01-26 21:42:50",
                "likes": 0,
                "downs": 0,
                "reply_id": 0,
                "username": "Kingsr",
                "avatar": "\\static\\images%user_defaule.png",
                "motto": "管理员",
                "time_format": "2019-01-26 21:42"
            },
            {
                "cid": 1507,
                "tid": 3972,
                "uid": 9,
                "content": "123",
                "create_time": "2019-01-26 21:43:22",
                "likes": 0,
                "downs": 0,
                "reply_id": 0,
                "username": "Kingsr",
                "avatar": "\\static\\images%user_defaule.png",
                "motto": "管理员",
                "time_format": "2019-01-26 21:43"
            }
        ],
        "pages": 1
    },
    "time": 1548928945
}
```

 **返回参数说明** 

|参数名|类型|说明|
| ----- |:-----:| ----- |
| cid | Int | 评论ID |
| tid | Int | 帖子ID |
| uid | Int | 评论人ID |
| content | String | 评论内容 |
| create_time | String | 评论时间 |
| likes | Int | 点赞总数 |
| downs | Int | 不同意数 |
| reply_id | Int | 回复的评论的id |
| username | String | 评论人昵称 |
| avatar | String | 评论人头像URL |
| motto | String | 评论人签名 |
| time_format | String | 格式化后的发帖时间 |
| pages | Int | 总页码 |

 **备注** 

- 更多返回错误代码请看首页的错误代码描述

#### postComment

**描述**

 - 提交评论

**请求URL**

 - `Api/getCommentList`

**请求方式**

 - `POST`

**请求参数**

| 参数名 | 类型 | 必选 | 默认值 | 说明 |
| ------|-------|-----|-----|-----|
| tid | Int | 是 | NULL | 评论帖子ID |
| replyCid | Int | 否 | NULL | 回复的评论ID |
| content | String | 是 | NULL | 评论内容 |

**返回示例**

```json
{
    "code": 0,
    "msg": "评论成功",
    "url": "",
    "time": 1548928945
}
```

 **返回参数说明** 

|参数名|类型|说明|
| ----- |:-----:| ----- |
| msg | String | 成功提示信息 |
| url | String | 跳转URL |

 **备注** 

- 成功后应将URL跳转至返回值
- 更多返回错误代码请看首页的错误代码描述


#### auth

**描述**

 - 快速验证权限

**请求URL**

 - `Api/auth/name/{$name}/uid/{$uid}`

**请求方式**

 - `GET`

**请求参数**

| 参数名 | 类型 | 必选 | 默认值 | 说明 |
| ------|-------|-----|-----|-----|
| name | String | 是 | NULL | 鉴权项目 |
| uid | Int | 是 | NULL | 被鉴权人ID |

**返回示例**

正确示例

```json
{
    "code": 0,
    "msg": true,
    "time": 1549081481
}
```

错误示例

```json
{
    "code": 105010,
    "msg": "无权限",
    "time": 1549081752
}
```

 **返回参数说明** 

|参数名|类型|说明|
| ----- |:-----:| ----- |
| msg | Bool | 鉴权结果 |

 **备注** 

- 该功能仅在已登录账号且账号具有`admin`权限的情况下可使用
- `true`为具有权限，`false`为不具有权限
- 更多返回错误代码请看首页的错误代码描述