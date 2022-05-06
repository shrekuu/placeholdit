# 占位图服务

> 主要为使占位图片缓存到浏览器, 避免每次都下载新的, 都支持 https 方式请求
> url: https://placeholdit.projects.linwise.com

## Todo

- 缓存 v3 版本的图片

## 用法

### v3 彩色图片

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: category(分类, 可选, 字符串, 默认为 "places", 所有分类为: people, places, things)
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v3?dimension=300x200" target="_blank">http://placeholdit.projects.linwise.com/v3?dimension=300x200</a>
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=2" target="_blank">http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=2</a>
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=3&category=things" target="_blank">http://placeholdit.projects.linwise.com/v3?dimension=300x200&key=3&category=things</a>
* 图片来自: <a href="https://placem.at" target="_blank">https://placem.at</a>

示例图片: 

<a href="http://placeholdit.projects.linwise.com/v3?dimension=300x200" target="_blank">http://placeholdit.projects.linwise.com/v3?dimension=300x200</a>

![images/v3-demo-image.jpg](images/v3-demo-image.jpg)

---

### v2 彩色图片

> lorempixel.com 图片在中国内地访问不到, 悲伤的故事, 请使用 v3 版本.

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: category(分类, 可选, 字符串, 默认为 "people", 所有分类为: abstract,animals,business,cats,city,food,nightlife,fashion,people,nature,sports,transport,technics)
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v2?dimension=300x300" target="_blank">http://placeholdit.projects.linwise.com/v2?dimension=300x300</a>
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=2" target="_blank">http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=2</a>
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=3&category=city" target="_blank">http://placeholdit.projects.linwise.com/v2?dimension=300x200&key=3&category=city</a>
* 图片来自: <a href="http://lorempixel.com" target="_blank">http://lorempixel.com</a>

示例图片: 

<a href="http://placeholdit.projects.linwise.com/v2?dimension=300x300" target="_blank">http://placeholdit.projects.linwise.com/v2?dimension=300x300</a>

![images/v2-demo-image.jpg](images/v2-demo-image.jpg)

---

### v1 纯灰图片

* 参数: dimension(长宽, 必选, 字符串, 如: "300x200")
* 参数: key(标识键, 可选, 整数, 默认为 "1"), 固定取某一张图片时使用
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v1?dimension=300x200" target="_blank">http://placeholdit.projects.linwise.com/v1?dimension=300x200</a>
* 示例 url: <a href="http://placeholdit.projects.linwise.com/v1?dimension=300x200&key=2" target="_blank">http://placeholdit.projects.linwise.com/v1?dimension=300x200&key=2</a>
* 图片来自: <a href="http://placeholder.com" target="_blank">http://placeholder.com</a>

示例图片: <a href="http://placeholdit.projects.linwise.com/v1?dimension=300x200&key=233" target="_blank">http://placeholdit.projects.linwise.com/v1?dimension=300x200&key=233</a>

![images/v1-demo-image.jpg](images/v1-demo-image.jpg)