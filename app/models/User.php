<?php

    class User
        extends Eloquent
    {

        /**
         * The database table used by the model.
         *
         * @var string
         */

        public $timestamps = false;

        protected $table = 'user';

        protected $primaryKey = "id";

        public function sentMessages()
        {
            return $this->hasMany("Message", "id", "sender_id");
        }

        public function receivedMessages()
        {
            return $this->hasMany("Message", "id", "receiver_id");
        }

    }
