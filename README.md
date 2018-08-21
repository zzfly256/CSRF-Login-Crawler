# CSRF Login Crawler

应小师弟所托，封装了一个可以成功绕过 CSRF 表单验证的 PHP 登录爬虫。目前测试成功的案例:

- `thinkphp`
- `laravel`
- `正方教务系统` 
- 更多使用 session + csrf token 方式的表单验证的系统欢迎大家测试与 issue



## 类的属性

- `loginPageUrl` : 登录页地址

- `loginTargetUrl` : 登录(POST请求)的目标

- `contentUrl` : 要跳转的内容页

- `refererUrl` : 请求头部的 refere

## 类的方法

### `curlRequest($url, $post = '', $cookie = '', $returnCookie = 1)`

- 说明: 对 cURL 的封装，方便使用

- 参数


| 参数   |      说明      |  必须  |  Demo |
|----------|:-------------:|-------|-----|
| url |  发送请求的目标地址 | 是 | "http://www.zzfly.net" | 
| post | 是否使用 POST 方法，如果使用传入所需 POST 的关联数组即可 | 是 | [ "username" => "user", "password" => "test"] |
| cookie | 是否在请求时带上 cookie，如果需要请传入原始 cookie 数据 | 是 | PHPSESSID=8rv5htnkng3n2j6pmv61botgt2; path=/ |
| returnCookie | 是否返回 cookie，默认返回 | 否 | true |

- 返回值

```
// 带有 cookie: 关联数组

[
	"cookie" = "PHPSESSID=8rv5htnkng3n2j6pmv61botgt2; path=/",
	"content" = "*****"
]

```


```
// 不带 cookie: 字符串

	"*****" 

```

### `post($user, $password)`

- 说明: 发送登录请求

- 参数


| 参数   |      说明      |  必须  |  Demo |
|----------|:-------------:|-------|-----|
| user |  用户名 | 是 | "user" | 
| password | 密码 | 是 | "passoerd" |


- 返回值

```
// 字符串

	"*****" 

```

- 获取这个方法命名为 fetch 会更好（逃

### 构造方法

$loginPageUrl, $loginTargetUrl, $contentUrl, $refererUrl

- 参数

| 参数   |      说明      |  必须  |  Demo |
|----------|:-------------:|-------|-----|
| loginPageUrl |  登录页地址 | 是 | "http://127.0.0.1/auth/login" | 
| loginTargetUrl | POST 请求地址 | 是 | "http://127.0.0.1/auth/user" |
| contentUrl | 登录成功之后需要获取内容的页面地址 | 是 | "http://127.0.0.1/admin" |
| refererUrl | referer 来源 | 否 | "http://127.0.0.1/" |

## 如何使用
![img](http://wx2.sinaimg.cn/large/d3ea10bdgy1fuhhmkkg1vj20mb09idgf.jpg)

- 源码请参照 demo.php