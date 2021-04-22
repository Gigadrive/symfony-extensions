<?php
/*
 * Copyright (C) 2018-2021 Gigadrive - All rights reserved.
 * https://gigadrivegroup.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://gnu.org/licenses/>
 */

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\Entry;

use Gigadrive\Bundle\SymfonyExtensionsBundle\SEO\MetaTagEntry;
use function sprintf;

class ProfileMetaTagEntry extends MetaTagEntry {
	/**
	 * @var string GENDER_MALE
	 */
	public const GENDER_MALE = "male";

	/**
	 * @var string GENDER_FEMALE
	 */
	public const GENDER_FEMALE = "female";

	private $firstName;
	private $lastName;
	private $gender;
	private $username;

	public function __construct(?string $firstName = null, ?string $lastName = null, ?string $gender = null, ?string $username = null) {
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->gender = $gender;
		$this->username = $username;
	}

	/**
	 * @return string|null
	 */
	public function getFirstName(): ?string {
		return $this->firstName;
	}

	/**
	 * @param string|null $firstName
	 * @return ProfileMetaTagEntry
	 */
	public function setFirstName(?string $firstName): self {
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getLastName(): ?string {
		return $this->lastName;
	}

	/**
	 * @param string|null $lastName
	 * @return ProfileMetaTagEntry
	 */
	public function setLastName(?string $lastName) {
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getGender(): ?string {
		return $this->gender;
	}

	/**
	 * @param string|null $gender
	 * @return ProfileMetaTagEntry
	 */
	public function setGender(?string $gender) {
		$this->gender = $gender;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getUsername(): ?string {
		return $this->username;
	}

	/**
	 * @param string|null $username
	 * @return ProfileMetaTagEntry
	 */
	public function setUsername(?string $username): self {
		$this->username = $username;

		return $this;
	}

	public function getAsOGTagString(): string {
		$firstNameTag = !is_null($this->firstName) ? sprintf(
			'<meta name="profile:first_name" content="%s"/>',
			$this->firstName
		) : "";

		$lastNameTag = !is_null($this->lastName) ? sprintf(
			'<meta name="profile:first_name" content="%s"/>',
			$this->lastName
		) : "";

		$genderTag = !is_null($this->gender) ? sprintf(
			'<meta name="profile:first_name" content="%s"/>',
			$this->gender
		) : "";

		$userNameTag = !is_null($this->username) ? sprintf(
			'<meta name="profile:first_name" content="%s"/>',
			$this->username
		) : "";

		return $firstNameTag . $lastNameTag . $genderTag . $userNameTag;
	}
}