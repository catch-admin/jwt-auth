<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    // 排除目录
    //->exclude('packages')
    //// ->notPath('./packages/test.php')
    // in 配置需要规则的目录
    ->in([
        __DIR__ . DIRECTORY_SEPARATOR . 'src/',

        __DIR__ . DIRECTORY_SEPARATOR . 'config/',
    ])
    // 排除 . 开头的文件
    ->ignoreDotFiles(true)
    // vcs 文件
    ->ignoreVCS(true);

$config = new Config();

return $config->setRules([
    '@PSR1' => true, // psr1

    '@PSR12' => true, // psr12 规范

    'binary_operator_spaces' => true, // 二元操作符号空格 $a=1 => $a = 1;

    'array_syntax' => [
        'syntax' => 'short', // array('1') => ['1']
    ],

    'no_trailing_comma_in_singleline_array' => true, // -$a = array('sample',  ); => $a = array('sample');

    'trim_array_spaces' => true, // array( 'a', 'b' ); => array('a', 'b')

    'standardize_not_equals' => true, // "!=" => "<>"

    'mb_str_functions' => true, // str_len 替换成 mb_str

    'magic_constant_casing' => true, // __dir__ => __DIR__

    'native_function_casing' => true, // STRLEN($str); => strlen($str);

    'cast_spaces' => true, // (int)$b => (int) $b

    'ordered_traits' => true, // use Z; use A;  => use A; use Z;

    'self_accessor' => true, // A::a() => self::a()

    'simplified_if_return' => true, // if ($foo) { return true; } return false; => return (bool) ($foo)      ;

    'no_unused_imports' => true, //  use \DateTime; -use \Exception; => use \DateTime;

    'not_operator_with_successor_space' => true, // if (!$bar)  => if (! $bar)

    'ternary_to_elvis_operator' => true, // -$foo = $foo ? $foo : 1; => $foo = $foo ?  : 1;

    /**
     * // function example($b) {
    if ($b) {
    return;
    }
    - return;
     */
    'no_useless_return' => true,

    /**
     * function a() {
    -    $a = 1;
    -    return $a;
    +    return 1;
     */
    'return_assignment' => true,

    /**
     * -<?php return null;
    +<?php return;
     */
    'simplified_null_return' => true,

    /**
     * $foo = [
    -   'bar' => [
    -    'baz' => true,
    -  ],
    +    'bar' => [
    +        'baz' => true,
    +    ],
     */
    'array_indentation' => true,

    'no_spaces_around_offset' => true,

    'concat_space' => true,  // $a.$b => $a . $b
])->setFinder($finder);