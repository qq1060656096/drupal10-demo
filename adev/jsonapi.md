```text
在 Drupal 10 中使用 JSON API 的基本步骤如下:

安装并启用 JSON API 模块。
在管理界面配置要为其启用 JSON API 的内容类型,如文章、页面、媒体等。
通过发送 HTTP 请求访问 JSON API 端点。例如:
获取所有文章:GET /jsonapi/node/article
获取 ID 为 123 的文章:GET /jsonapi/node/article/123
创建一个文章:POST /jsonapi/node/article
请求和响应格式遵循 JSON API 规范。例如:
请求体应该是一个 JSON 对象,包含 data 和 relationships 字段。
响应体是一个 JSON 对象,包含 data、links、meta 等信息。
可以在请求中自定义查询参数:
fields - 指定返回哪些字段
page - 分页
filter - 过滤
include - 包含关联资源
默认情况下需要授权。可以配置disable_auth模块禁用授权。
在自定义模块中可以利用 JSON API 的 Serializer 和 Normalizer 来解析请求和生成响应。
配置 CORS 和 CSRF 设置以启用跨域 AJAX 请求。
通过接口文档模块可以自动生成 JSON API 的 OpenAPI 文档。
遵循 RESTful 最佳实践,使用 HTTP 动词、状态码、缓存等来设计 API。
综上,通过发送标准的 JSON API 请求来获取和操作 Drupal 中的内容。
```


在 Drupal 10 中,可以通过 JSON API 模块提供的接口来获取菜单数据:

1. 确保 JSON API 模块已启用,并在管理界面中为菜单(menu)内容类型启用 JSON API 支持。

2.发送GET请求访问菜单列表端点:

```
GET /jsonapi/menu
```

3. 发送GET请求访问单个菜单端点,其中{menu}是菜单ID:

```
GET /jsonapi/menu/{menu}
```
4. 默认情况下会返回每个菜单的基本信息,可以通过包含fields查询参数指定需要的字段:

```
GET /jsonapi/menu?fields[menu]=id,label,description
```
5. 要包含菜单链接,需要通过include参数指定relationships:
```
GET /jsonapi/menu?include=menu_links
```
6. 可以通过filter、page、sort等参数进行过滤、分页和排序。
7. 响应格式遵循JSON API规范,包含菜单列表或单个菜单信息。
8. 在代码中可以利用JSON API的Serializer和Normalizer来解析响应。
9. 记得处理授权,或禁用接口的授权检查。
10. 可以通过配置只公开部分菜单,或者限制字段来控制暴露信息。
所以In summary, the JSON API module provides a standard way to get and manipulate menu data in Drupal 10 via REST API.
