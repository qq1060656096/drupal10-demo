在 Drupal 10 中，要使用 JSON API 获取菜单的内容，您可以按照以下步骤操作：

确保已经安装并启用了 "JSON:API" 模块。如果尚未安装，请登录到 Drupal 后台管理界面，转到 "Extend"（或 "模块"）选项卡，然后搜索并安装 "JSON:API" 模块。

获取菜单的 ID 或名称。您可以在 Drupal 后台管理界面中的 "Structure"（或 "结构"）菜单下的 "Menus"（或 "菜单"）部分找到菜单及其对应的 ID 或名称。

构建 JSON API 请求的 URL。根据您的需求，您可以使用以下两种 URL 格式之一来获取菜单的内容：

使用菜单的 ID：

/jsonapi/menu_items/{menu}
```
{
    "jsonapi": {
        "version": "1.0",
        "meta": {
            "links": {
                "self": {
                    "href": "http://jsonapi.org/format/1.0/"
                }
            }
        }
    },
    "data": [
        {
            "type": "menu_link_content--menu_link_content",
            "id": "standard.front_page",
            "attributes": {
                "description": "",
                "enabled": true,
                "expanded": false,
                "menu_name": "main",
                "meta": [],
                "options": [],
                "parent": "",
                "provider": "standard",
                "route": {
                    "name": "<front>",
                    "parameters": []
                },
                "title": "首页",
                "url": "/",
                "weight": 0
            }
        }
    ],
    "links": {
        "self": {
            "href": "http://localhost/jsonapi/menu_items/main"
        }
    }
}
```


将 {menu_item_id} 替换为实际的菜单项ID，即您要获取的特定菜单项的ID。

发起GET请求以获取菜单项的JSON数据：您可以使用任何适合您的方式来发送请求，比如使用命令行工具如cURL，或者使用编程语言进行HTTP请求。

以下是一个示例cURL命令：

bash
curl -H "Accept: application/vnd.api+json" https://example.com/jsonapi/menu_items/{menu_item_id}
请将 https://example.com 替换为您的Drupal网站地址，同时将 {menu_item_id} 替换为实际的菜单项ID。

解析获取到的JSON数据以获得菜单项的内容。

通过遵循以上步骤，您可以使用JSON:API_Menu_Items模块在Drupal中获取特定菜单项的内容。请确保您具备正确的权限和URL，并且可以成功访问JSON API。如果仍然遇到问题，请提供详细的错误信息以方便我为您提供进一步的帮助。
