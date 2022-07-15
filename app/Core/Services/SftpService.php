<?php

namespace App\Core\Services;

use phpseclib3\Net\SFTP;

class SftpService
{
    private SFTP $client;

    public function __construct()
    {
        $this->client = new SFTP(
            config('core.sftp.host'),
            config('core.sftp.port')
        );
    }

    public function put(string $saveTo, string $localFile): bool
    {
        if (!$this->client->login(
            config('core.sftp.username'),
            config('core.sftp.password'),
        )) {
            return false;
        }

        $saveTo = config('core.sftp.root_path').'/'.trim($saveTo, '/');
        $this->client->mkdir($saveTo, -1, true);

        return $this->client->put(
            $saveTo.'/'.basename($localFile),
            $localFile,
            SFTP::SOURCE_LOCAL_FILE
        );
    }
}
