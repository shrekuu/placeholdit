# 占位图服务

> 主要为使占位图片缓存到浏览器, 避免每次都下载新的, 都支持 https 方式请求

## 用法


### v3

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: category(分类, 可选, 字符串, 默认为 "places", 所有分类为: people, places, things)
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: http://placeholdit.projects.linwise.com/v3?dimension=300x200
* 示例 url: http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=2
* 示例 url: http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=3&category=things
* 图片来自: <a href='https://placem.at'>https://placem.at</a>    


### v1

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: http://placeholdit.projects.linwise.com/v1?dimension=300x200
* 示例 url: http://placeholdit.projects.linwise.com/v1?dimension=300x200&key=2
* 了解更多: http://placeholder.com


### v2

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: category(分类, 可选, 字符串, 默认为 "people", 所有分类为: abstract,animals,business,cats,city,food,nightlife,fashion,people,nature,sports,transport,technics)
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: http://placeholdit.projects.linwise.com/v2?dimension=300x200
* 示例 url: http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=2
* 示例 url: http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=3&category=cats
* 了解更多: http://lorempixel.com/
