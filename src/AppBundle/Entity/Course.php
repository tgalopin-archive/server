<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Dunglas\ApiBundle\Annotation\Iri;
use Webmozart\Assert\Assert;

/**
 * @see http://schema.org/Organization Documentation on Schema.org
 *
 * @ORM\Entity
 * @ORM\Table(name="wink_courses")
 * @Iri("http://schema.org/Organization")
 */
class Course
{
    const TYPE_C = 'c';
    const TYPE_TD = 'td';
    const TYPE_TP = 'tp';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Iri("https://schema.org/name")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     * @Iri("https://schema.org/Text")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     * @Iri("https://schema.org/Integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     * @Iri("https://schema.org/Text")
     */
    private $key;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="courses", cascade={"all"})
     * @ORM\JoinTable(name="wink_users_courses")
     */
    private $users;

    /**
     * @param string $uv
     * @param string $type
     * @param int $number
     */
    public function __construct($uv, $type, $number)
    {
        Assert::string($uv);
        Assert::oneOf($type, [ self::TYPE_C, self::TYPE_TD, self::TYPE_TP ]);
        Assert::integer($number);

        $this->uv = $uv;
        $this->type = $type;
        $this->number = $number;

        $this->users = new ArrayCollection();
        $this->refreshKey();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uv
     *
     * @param string $uv
     * @return Course
     */
    public function setUv($uv)
    {
        Assert::string($uv);

        $this->uv = $uv;
        $this->refreshKey();

        return $this;
    }

    /**
     * Get uv
     *
     * @return string
     */
    public function getUv()
    {
        return $this->uv;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Course
     */
    public function setType($type)
    {
        Assert::oneOf($type, [ self::TYPE_C, self::TYPE_TD, self::TYPE_TP ]);

        $this->type = $type;
        $this->refreshKey();

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Course
     */
    public function setNumber($number)
    {
        Assert::integer($number);

        $this->number = $number;
        $this->refreshKey();

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Add users
     *
     * @param User $users
     * @return Course
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return User[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Refresh this course key (called on edition of key field)
     */
    private function refreshKey()
    {
        $this->key = implode('-', [ $this->uv, $this->type, $this->number ]);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return Course
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
}
