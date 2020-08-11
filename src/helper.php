<?php

if (!function_exists('result')) {
    /**
     * 短信发送结果返回封装格式
     *
     * @param int $code
     * @param string $message
     * @param array|null $data
     * @return array
     */
    function result(int $code, string $message = null, array $data = null)
    {
        $result = [
            'code' => $code,
            'message' => $message,
        ];
        if (!empty($data)) {
            $result['data'] = $data;
        }
        return $result;
    }
}
