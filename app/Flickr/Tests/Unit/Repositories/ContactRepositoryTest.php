<?php

namespace App\Flickr\Tests\Unit\Repositories;

use App\Flickr\Repositories\ContactRepository;
use Tests\TestCase;

class ContactRepositoryTest extends TestCase
{
    private ContactRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ContactRepository::class);
    }

    public function testCreateContact()
    {
        $nsid = $this->faker->uuid;
        $contact = $this->repository->create([
            'nsid' => $nsid,
        ]);

        $this->assertDatabaseHas('flickr_contacts', [
            'nsid' => $nsid,
        ]);

        $duplicatedContact = $this->repository->create([
            'nsid' => $nsid,
        ]);

        $this->assertTrue($duplicatedContact->is($contact));

        $contact->delete();

        $this->assertSoftDeleted($this->repository->create([
            'nsid' => $nsid,
        ]));
    }

    public function testFindByNsid()
    {
        $nsid = $this->faker->uuid;
        $contact = $this->repository->create([
            'nsid' => $nsid,
        ]);

        $this->assertTrue($this->repository->findByNsid($nsid)->is($contact));

        $contact->delete();
        $this->assertTrue($this->repository->findByNsid($nsid)->is($contact));
    }
}
