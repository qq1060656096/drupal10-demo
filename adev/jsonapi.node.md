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
