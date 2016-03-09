<?php

namespace App\Models\Relationships;

use Phalcon\Mvc\Model;

class AccessTokenGrantsCentre extends Model
{
    public $access_token_id;

    public $centre_id;

    public function initialize()
    {
        $this->setSource("access_token_grants_centre");

        $this->belongsTo(
            'access_token_id',
            'App\Models\AccessToken',
            'id',
            [
                'alias' => 'AccessToken',
                'foreignKey' => [
                    'message' => 'The access_token_id does not exist on the AccessToken model'
                ],
            ]
        );

        $this->belongsTo(
            'centre_id',
            'App\Models\Centre',
            'id',
            [
                'alias' => 'Centre',
                'foreignKey' => [
                    'message' => 'The centre_id does not exists on the Centre model'
                ]
            ]
        );
    }

    public function validation()
    {
        $this->validate(
            new PresenceOf(
                [
                    'field'     => 'access_token_id',
                ]
            )
        );
        $this->validate(
            new PresenceOf(
                [
                    'field'     => 'centre_id',
                ]
            )
        );
        $this->validate(
            new Uniqueness(
                [
                    'field'     => ['access_token_id', 'centre_id'],
                ]
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}