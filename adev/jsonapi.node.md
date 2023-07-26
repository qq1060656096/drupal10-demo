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
