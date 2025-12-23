<?php

namespace App\Services;

use App\Models\GoogleAccount;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Carbon\Carbon;

class GoogleCalendarService
{
    protected function client(GoogleAccount $account): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));
        $client->setScopes(config('google.scopes'));
        $client->setAccessType('offline');

        // Set token dari database
        $client->setAccessToken([
            'access_token' => $account->access_token,
            'expires_in'   => $account->token_expires_at->diffInSeconds(now()),
        ]);

        // Refresh token jika expired
        if ($client->isAccessTokenExpired()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $account->refresh_token
            );

            $account->update([
                'access_token' => $newToken['access_token'],
                'token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);
        }

        return $client;
    }

    public function createReminder(
        GoogleAccount $account,
        string $title,
        Carbon $start,
        Carbon $end,
        string $timezone = 'Asia/Makassar'
    ): void {
        $client = $this->client($account);
        $service = new Calendar($client);

        $event = new Event([
            'summary' => $title,
            'start' => [
                'dateTime' => $start->toIso8601String(),
                'timeZone' => $timezone,
            ],
            'end' => [
                'dateTime' => $end->toIso8601String(),
                'timeZone' => $timezone,
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        $service->events->insert('primary', $event);
    }
}
