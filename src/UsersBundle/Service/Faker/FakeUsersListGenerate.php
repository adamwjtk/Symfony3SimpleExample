<?php

namespace UsersBundle\Service\Faker;

use Faker\Factory as Fake;

class FakeUsersListGenerate
{
    /**
     * @var array
     */
    private $fakeUserArray;

    /**
     * generated and returned fake users
     * @param int $amount
     * @return array
     */
    public function generateList(int $amount): array
    {
        $this->fakeUserArray = [];
        $fakeUserFactory = Fake::create();
        $counter = 0;
        while ($amount > $counter) {
            $this->fakeUserArray[$counter]['LastName'] = $fakeUserFactory->lastName;
            $this->fakeUserArray[$counter]['FirstName'] = $fakeUserFactory->firstName;
            $this->fakeUserArray[$counter]['Address']['street'] = $fakeUserFactory->streetName;
            $this->fakeUserArray[$counter]['Address']['number'] = $fakeUserFactory->buildingNumber;
            $this->fakeUserArray[$counter]['Address']['city'] = $fakeUserFactory->city;
            $this->fakeUserArray[$counter]['Address']['postalCode'] = $fakeUserFactory->postcode;
            $counter++;
        }

        return $this->fakeUserArray;
    }
}