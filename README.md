# Domain-Resolver-PHP

## 技术栈

- PHP 8.0（7.4）

## How to use

1. 首先你得把它上传到一台高速的server
2. 然后你得进行一个php环境的配
3. 接着你得跟着这个文档进行一个请求的学
4. 最后你要进行一个请求的发

## 请求参数

请求Url: `GET HTTP/1.1 https://domain/path/to/resolve.php`

### 参数: 

| 参数     | 是否必须   | 支持类型 / 注解                |
|--------|--------|--------------------------|
| domain     | 必填 | 域名       |

### 返回示例：

若一切正确，请求后你会得到这样一串json：

```
{
    "code": 0,
    "msg": "OK",
    "data": {
        "IPv4": [
            {
                "organization": "Microsoft Azure",
                "longitude": 103.8547,
                "timezone": "Asia\/Singapore",
                "isp": "Microsoft Azure",
                "offset": 28800,
                "asn": 8075,
                "asn_organization": "MICROSOFT-CORP-MSN-AS-BLOCK",
                "country": "Singapore",
                "ip": "20.205.243.166",
                "latitude": 1.2929,
                "continent_code": "AS",
                "country_code": "SG"
            }
        ],
        "IPv6": []
    },
    "limit": 4
}
```

返回不是这样的自己排查去，这里没有troubleshooting

## 一些话的说

本源码在PhpStorm里bug 0检出

有一个小问题就是如果domain是 www.google 这种他也会当成域名，但是不会返回任何内容
可是我懒了，所以没继续完善

很多小细节可能处理的不是很好，看到了可以提个pr或issue

## Special

感谢 [Gong_cx](https://github.com/Gongcxgithub/) 的重构和支持

IP Location API由ip.sb提供

<hr />
<p align="center">Powered by GBCLStudio.</p>
