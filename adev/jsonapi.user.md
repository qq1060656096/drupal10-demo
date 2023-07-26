在 Drupal 10 中,可以通过 JSON API 模块以 JSON 格式获取用户数据,具体步骤如下:

1. 确保网站已安装并启用 JSON API 模块。

2. 通过如下端点访问用户数据:

```
yoursite.com/jsonapi/user/user
```

3. 可以添加参数进行过滤、排序、分页等,例如只获取用户名包含 "test" 的用户:

```
yoursite.com/jsonapi/user/user?filter[name][value]=test
```

4. 可以通过字段参数指定需要返回的用户字段,例如只需要邮箱和创建时间:

```
yoursite.com/jsonapi/user/user?fields[user]=email,created
```

5. JSON API 会返回 JSON 格式的用户数据,你可以在代码中解析处理。

6. 也可以发送 POST 请求到 /jsonapi/user/user 端点创建新用户。

7. 更多详细用法可参考 JSON API 模块文档。

所以利用 JSON API 可以方便地使用 JSON 格式与 Drupal 10 进行用户数据的获取和操作。

# ==============

如果你想获取Drupal 10中某个指定用户的详细信息,可以这样操作:

1. 确保JSON API模块已启用

2. 构造获取单个用户数据的API URL,格式如下:

```
yoursite.com/jsonapi/user/user/{用户ID}
```

将{用户ID}替换为你想获取的用户的用户ID。

例如获取ID为5的用户信息:

```
yoursite.com/jsonapi/user/user/5
```

3. 发送GET请求到该API端点

4. JSON API会返回该用户的详细信息,包括用户名、邮箱、创建时间等字段

5. 你可以在代码中解析处理该JSON用户数据

6. 也可以添加字段参数指定需要返回的用户字段:

```
yoursite.com/jsonapi/user/user/5?fields[user]=name,mail,created
```

这就可以获取ID为5的用户的用户名、邮箱和创建时间。

利用JSON API以JSON格式获取指定用户信息,可以方便地用于前后端分离的Drupal网站开发。
