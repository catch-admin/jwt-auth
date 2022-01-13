<?php


namespace catchAdmin\jwt\command;

use think\console\Input;
use think\console\Output;
use think\facade\App;
use think\facade\Filesystem;

class JwtCommand extends \think\console\Command
{
    public function configure()
    {
        $this->setName('jwt:create')
            ->setDescription('create jwt secret and create config file');
    }

    public function execute(Input $input, Output $output)
    {
        $key  = md5(uniqid().time().rand(0, 60));

        $path = app()->getAppPath().'..'.DIRECTORY_SEPARATOR.'.env';

        if (file_exists($path)
            && strpos(file_get_contents($path), '[JWT]')
        ) {
            $output->writeln('JWT_SECRET is exists');
        } else {
            file_put_contents(
                $path,
                PHP_EOL."[JWT]".PHP_EOL."SECRET=$key".PHP_EOL,
                FILE_APPEND
            );
            $output->writeln('JWT_SECRET has created');
        }

        $this->createJWTConfig($output);
    }

    /**
     * @desc 创建 jwt config
     *
     * @time 2022年01月13日
     * @param $output
     */
    public function createJWTConfig($output)
    {
        $jwt = $this->app->getBasePath() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'jwt.php';

        if (file_exists($jwt)) {
            return;
        }

        $from = dirname(__DIR__, 2) .DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'jwt.php';

        copy($from, $jwt);

        if (! file_exists($jwt)) {
            $output->writeln('Create config file failed');
        }
    }
}
