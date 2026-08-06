<?php

it('renders the general journal ledger page', function () {
    $this->get(route('generalJournal.ledger'))
        ->assertStatus(200)
        ->assertViewIs('ledgers.account-ledger.generalJournal-ledger-view');
});
