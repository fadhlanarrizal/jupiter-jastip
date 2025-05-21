<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Sheets;

class GoogleSheetService extends ServiceProvider
{protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(config('google.credentials_path'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $client->setApplicationName('Laravel Jastip Orders');

        $this->service = new Google_Service_Sheets($client);
        $this->spreadsheetId = config('google.spreadsheet_id');
    }

    public function appendRow(array $data)
    {
        $range = 'Sheet1!A2'; // pastikan nama sheet sesuai
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => [$data],
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];

        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $range,
            $body,
            $params
        );
    }
}
