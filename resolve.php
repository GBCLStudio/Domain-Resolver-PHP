<?php
/**
 * @author GBCLStudio <support@gbclstudio.cn>
 * @copyright 2023 GBCLStudio
 * 弊端： 获取到的是本地的dns解析，但是如果服务器多也许就能忽略这个问题（？）
 */
header('Content-type: text/json;charset=utf-8');
header('X-Powered-By: GBCLStudio PHP-Project');

$domain = $_GET['domain'];

if (!$domain) {
    exit(json_encode(['code' => -2, 'msg' => 'Error', 'data' => 'NoRequestDomain']));
}

echo json_encode(doResolveHandle($domain), 480);

/**
 * 用checkdnsrr代替gethostbyname
 * 不过感觉这玩意是在初筛（.
 *
 * @return bool
 */
function checkValidation(): bool
{
    if (!checkdnsrr(DOMAIN, 'A') && !checkdnsrr(DOMAIN, 'AAAA')) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param string $domain
 *
 * @return array
 */
function DNSRecordHandle(string $domain): array
{
    $origin = dns_get_record($domain, DNS_A + DNS_AAAA);

    return [
        array_column($origin, 'ip'),
        array_column($origin, 'ipv6'),
    ];
}

/**
 * 接收从getRecordIPInfo()参数传递的数据并通过API请求获取详细信息.
 *
 * @param string $ip
 *
 * @return array
 */
function getIPInfoHandle(string $ip): array
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.ip.sb/geoip/'.trim($ip),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result;
}

/**
 * 获取dns记录详细信息
 * 通过foreach遍历向getIPInfoHandle()提交相应IP
 * 限制数据量为4以保证速率，防止大规模遍历.
 *
 * @param array $records
 *
 * @return array|array[]
 */
function getRecordIPInfo(array $records): array
{
    $result = ['IPv4' => [], 'IPv6' => []];
    foreach ($records as $key => $value) {
        if ($value) {
            foreach ($value as $index => $item) {
                if ($index === 4) {
                    break;
                }
                $result[$key === 0 ? 'IPv4' : 'IPv6'][] = getIPInfoHandle($item);
            }
        }
    }

    return $result;
}

/**
 * @param string $domain
 *
 * @return array|array[]
 */
function doResolveHandle(string $domain): array
{
    if (checkValidation()) {
        return [
            'code'  => 0,
            'msg'   => 'OK',
            'data'  => getRecordIPInfo(DNSRecordHandle($domain)),
            'limit' => 4,
        ];
    } else {
        exit(json_encode([
            'code' => -1,
            'msg'  => 'Error',
            'data' => 'RequestNotValidate',
        ]));
    }
}
