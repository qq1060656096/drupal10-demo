在 Drupal 10 中,可以使用 JSON API 模块以 JSON 格式获取节点的数据,具体步骤如下:

1. 在 Drupal 网站后台启用 JSON API 模块。

2. 创建一些内容节点,可以是文章、页面等不同内容类型。

3. 通过如下端点获取所有文章节点数据:

```
yoursite.com/jsonapi/node/article
```

4. 可以添加 filter 进行过滤,例如只获取已发布的文章:

```
yoursite.com/jsonapi/node/article?filter[status]=1
```

5. 通过 fields 参数指定需要的字段:

```
yoursite.com/jsonapi/node/article?fields[node--article]=title,body
```

6. 通过包含节点 ID 获取单个节点数据:

```
yoursite.com/jsonapi/node/article/{节点ID}
```

7. 也可以发送 POST 请求到该端点创建新节点。

8. 更多详细用法请参考 JSON API 模块文档。

综上,利用 JSON API 可以方便地获取和操作 Drupal 中的节点数据,实现前后端分离。


# =================

在Drupal 10中,可以通过JSON API模块获取启用的多语言内容:

1. 确保已在Drupal中启用多语言支持并配置了多个语言(比如英语、中文)。

2. 启用JSON API模块。

3. 通过如下端点可以获取所有语言的内容:

```
/jsonapi/node/article
```

4. 内容会包含当前请求语言的版本。

5. 可以添加language参数指定需要的语言:

```
/jsonapi/node/article?language=zh-hans
```

6. 这样就可以只获取中文内容。

7. 也可以在filter中过滤语言:

```
/jsonapi/node/article?filter[langcode]=zh-hans
```

8. 对于单个内容,直接访问其端点即可获取对应语言版本。

9. 还可以只获取某语言的标题和正文字段。

10. 在前端可以解析JSON数据展示翻译内容。

11. 根据语言参数切换获取不同语言内容。

利用JSON API获取多语言内容,可以轻松实现Drupal站点的国际化。


```text
在 Drupal 10 中，你可以使用 JSON:API 模块来支持多语言的 API 接口。以下是配置 JSON:API 模块以支持多语言的步骤：

确保启用了 JSON:API 模块：在 Drupal 的模块管理中，确保已经安装并启用了 JSON:API 模块。如果没有安装，请在 Drupal 的模块库中搜索并安装该模块。

配置多语言设置：进入网站的管理区域，导航至“Configuration” > “Regional and language” > “Languages” 页面。在这里，你可以启用和配置支持的语言。

配置 JSON:API 请求的多语言支持：

进入网站的管理区域，导航至“Configuration” > “Web services” > “JSON:API” 页面。
在“JSON:API language negotiation”部分，选择“Path prefix”选项，这样请求 URL 中可以包含语言前缀（例如：/en/jsonapi/node）。
在“JSON:API resources”部分，选择你想要启用多语言支持的资源类型，并勾选“Enable entity translation”选项。
配置内容类型的翻译：

进入要进行翻译的内容类型的编辑页面。
在“Manage fields”选项卡下，找到需要翻译的字段，并勾选“Enable translation”选项。对于多语言字段（如文本、文本域等），你还可以选择“Translate”或“Custom language”选项。
清除缓存：在 Drupal 的管理区域，导航至“Configuration” > “Development” > “Performance” 页面，点击“Clear all caches”按钮以清除缓存，确保配置生效。

经过以上步骤的配置后，你就可以在 JSON:API 中通过访问对应语言的 URL 来获取多语言支持的内容。

希望这些信息能够帮助到你！如果你还有其他问题，请随时提问。
```
