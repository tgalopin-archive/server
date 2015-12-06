<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Dunglas\ApiBundle\Annotation\Iri;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Webmozart\Assert\Assert;

/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ORM\Entity
 * @Iri("http://schema.org/Person")
 */
class User
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_OTHER = 'other';

    const STATUS_SINGLE = 'single';
    const STATUS_TAKEN = 'taken';
    const STATUS_COMPLICATED = 'complicated';

    const SEARCHING_PREF_MALE = 'male';
    const SEARCHING_PREF_FEMALE = 'female';
    const SEARCHING_PREF_OPEN = 'open';

    const SEARCHING_TYPE_CDI = 'cdi';
    const SEARCHING_TYPE_CDD = 'cdd';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Email address.
     *
     * @ORM\Column(type="string", length=200)
     * @Email(message="user.email.invalid")
     * @Iri("https://schema.org/email")
     */
    private $email;

    /**
     * @var string First name
     *
     * @ORM\Column(type="string", length=30)
     * @Type(type="string", message="user.firstName.invalid")
     * @Length(min=2, minMessage="user.firstName.tooShort", max=30, maxMessage="user.firstName.tooLong")
     * @Iri("https://schema.org/givenName")
     */
    private $firstName;

    /**
     * @var string Family name
     *
     * @ORM\Column(type="string", length=30)
     * @Type(type="string", message="user.lastName.invalid")
     * @Length(min=2, minMessage="user.lastName.tooShort", max=30, maxMessage="user.lastName.tooLong")
     * @Iri("https://schema.org/familyName")
     */
    private $lastName;

    /**
     * @var \DateTime Date of birth.
     *
     * @ORM\Column(type="date", nullable=true)
     * @Date(message="user.birthDate.invalid")
     * @Iri("https://schema.org/birthDate")
     */
    private $birthDate;

    /**
     * @var string Gender of the person.
     *
     * @ORM\Column(type="string", length=20)
     * @Type(type="string", message="user.gender.invalid")
     * @Expression("value in this.availableGenders()", message="user.gender.invalid")
     * @Iri("https://schema.org/gender")
     */
    private $gender;

    /**
     * @var string A user picture
     *
     * @ORM\Column(nullable=true)
     * @Iri("https://schema.org/image")
     */
    private $image;

    /**
     * @var Branch
     *
     * @ORM\ManyToOne(targetEntity="Branch", cascade={"all"})
     */
    private $branch;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Type(type="integer", message="user.semester.invalid")
     * @Iri("https://schema.org/Integer")
     */
    private $semester;

    /**
     * @var Course[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="users", cascade={"all"})
     * @ORM\JoinTable(name="wink_users_courses")
     */
    private $courses;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     * @Type(type="string", message="user.relationshipStatus.invalid")
     * @Expression("value in this.availableStatus()", message="user.relationshipStatus.invalid")
     * @Iri("https://schema.org/Text")
     */
    private $relationshipStatus = self::STATUS_SINGLE;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     * @Type(type="string", message="user.searchedRelationPreference.invalid")
     * @Expression("value in this.availableSearchingPreferences()", message="user.searchedRelationPreference.invalid")
     * @Iri("https://schema.org/Text")
     */
    private $searchedRelationPreference = self::SEARCHING_PREF_OPEN;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     * @Type(type="string", message="user.searchedRelationType.invalid")
     * @Expression("value in this.availableSearchingTypes()", message="user.searchedRelationType.invalid")
     * @Iri("https://schema.org/Text")
     */
    private $searchedRelationType = self::SEARCHING_TYPE_CDI;

    /**
     * @return array
     */
    public static function availableGenders()
    {
        return [
            self::GENDER_FEMALE,
            self::GENDER_MALE,
            self::GENDER_OTHER,
        ];
    }

    /**
     * @return array
     */
    public static function availableStatus()
    {
        return [
            self::STATUS_TAKEN,
            self::STATUS_COMPLICATED,
            self::STATUS_SINGLE,
        ];
    }

    /**
     * @return array
     */
    public static function availableSearchingPreferences()
    {
        return [
            self::SEARCHING_PREF_MALE,
            self::SEARCHING_PREF_FEMALE,
            self::SEARCHING_PREF_OPEN,
        ];
    }

    /**
     * @return array
     */
    public static function availableSearchingTypes()
    {
        return [
            self::SEARCHING_TYPE_CDD,
            self::SEARCHING_TYPE_CDI,
        ];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        Assert::string($email);

        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        Assert::string($firstName);

        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        Assert::string($lastName);

        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate(\DateTime $birthDate = null)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        Assert::oneOf($gender, self::availableGenders());

        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return User
     */
    public function setImage($image)
    {
        Assert::nullOrString($image);

        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set semester
     *
     * @param integer $semester
     * @return User
     */
    public function setSemester($semester)
    {
        Assert::nullOrInteger($semester);

        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return integer
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set relationshipStatus
     *
     * @param string $relationshipStatus
     * @return User
     */
    public function setRelationshipStatus($relationshipStatus)
    {
        Assert::oneOf($relationshipStatus, self::availableStatus());

        $this->relationshipStatus = $relationshipStatus;

        return $this;
    }

    /**
     * Get relationshipStatus
     *
     * @return string
     */
    public function getRelationshipStatus()
    {
        return $this->relationshipStatus;
    }

    /**
     * Set searchedRelationPreference
     *
     * @param string $searchedRelationPreference
     * @return User
     */
    public function setSearchedRelationPreference($searchedRelationPreference)
    {
        Assert::oneOf($searchedRelationPreference, self::availableSearchingPreferences());

        $this->searchedRelationPreference = $searchedRelationPreference;

        return $this;
    }

    /**
     * Get searchedRelationPreference
     *
     * @return string
     */
    public function getSearchedRelationPreference()
    {
        return $this->searchedRelationPreference;
    }

    /**
     * Set searchedRelationType
     *
     * @param string $searchedRelationType
     * @return User
     */
    public function setSearchedRelationType($searchedRelationType)
    {
        Assert::oneOf($searchedRelationType, self::availableSearchingTypes());

        $this->searchedRelationType = $searchedRelationType;

        return $this;
    }

    /**
     * Get searchedRelationType
     *
     * @return string
     */
    public function getSearchedRelationType()
    {
        return $this->searchedRelationType;
    }

    /**
     * Set branch
     *
     * @param Branch $branch
     * @return User
     */
    public function setBranch(Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Add courses
     *
     * @param Course $courses
     * @return User
     */
    public function addCourse(Course $courses)
    {
        $this->courses[] = $courses;

        return $this;
    }

    /**
     * Remove courses
     *
     * @param Course $courses
     */
    public function removeCourse(Course $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return ArrayCollection|Course[]
     */
    public function getCourses()
    {
        return $this->courses;
    }
}
