<?php

if (! function_exists('hash_md5')) {
    function hash_md5($login, $passwd)
    {
        return '0x'.md5($login.$passwd);
    }
}

if (! function_exists('hash_passwd')) {
    function hash_passwd($login, $passwd)
    {
        $hashType = config('app.hashing.pw_passwd_hash');

        switch ($hashType) {
            case 'md5':
                return '0x'.md5($login.$passwd);
                break;
            case 'base64md5':
                return base64_encode(md5($login.$passwd, true));
                break;
            default:
                return base64_encode(md5($login.$passwd, true));
                break;
        }
    }
}

if (! function_exists('split_name')) {
    function split_name($name)
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#'.$last_name.'#', '', $name));

        return [$first_name, $last_name];
    }
}

/*
 * Decode a value hashed by Hash id.
 */
if (! function_exists('decodeHashIdentifier')) {
    function decodeHashIdentifier($hashedValue)
    {
        $originalValue = \Hashids::decode($hashedValue);

        return isset($originalValue[0]) ? $originalValue[0] : false;
    }
}

function getPaymentMethodName($value)
{
    $labels = [
        0 => 'Não informado',
        101 => 'Cartão de crédito VISA',
        102 => 'Cartão de crédito MasterCard',
        103 => 'Cartão de crédito American Express',
        104 => 'Cartão de crédito Diners',
        105 => 'Cartão de crédito Hipercard',
        106 => 'Cartão de crédito Aura',
        107 => 'Cartão de crédito Elo',
        108 => 'Cartão de crédito PLENOCard',
        109 => 'Cartão de crédito PersonalCard',
        110 => 'Cartão de crédito JCB',
        111 => 'Cartão de crédito Discover',
        112 => 'Cartão de crédito BrasilCard',
        113 => 'Cartão de crédito FORTBRASIL',
        114 => 'Cartão de crédito CARDBAN',
        115 => 'Cartão de crédito VALECARD',
        116 => 'Cartão de crédito Cabal',
        117 => 'Cartão de crédito Mais!',
        118 => 'Cartão de crédito Avista',
        119 => 'Cartão de crédito GRANDCARD',
        120 => 'Cartão de crédito Sorocred',
        127 => 'Boleto',
        201 => 'Boleto Bradesco',
        202 => 'Boleto Santander',
        301 => 'Débito online Bradesco',
        302 => 'Débito online Itaú',
        303 => 'Débito online Unibanco',
        304 => 'Débito online Banco do Brasil',
        305 => 'Débito online Banco Real',
        306 => 'Débito online Banrisul',
        307 => 'Débito online HSBC',
        401 => 'Saldo PagSeguro',
        501 => 'Oi Paggo',
        701 => 'Depósito em conta - Banco do Brasil',
        702 => 'Depósito em conta - HSBC',
    ];

    if (array_key_exists($value, $labels)) {
        return $labels[$value];
    }

    return '';
}

function formatSize($bytes)
{
    $i = floor(log($bytes, 1024));

    return round($bytes / pow(1024, $i), [0, 0, 2, 2, 3][$i]).['B', 'kB', 'MB', 'GB', 'TB'][$i];
}
