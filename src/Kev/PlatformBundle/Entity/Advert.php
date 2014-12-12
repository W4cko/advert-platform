<?php

namespace Kev\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Advert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kev\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="Kev\PlatformBundle\Entity\Image", cascade={"persist"})
     *
     */
    private $image;
    /**
     * @ORM\ManyToMany(targetEntity="Kev\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Kev\PlatformBundle\Entity\Application", mappedBy="advert")*
     */
    private $applications;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="nbApplications", type="integer")
     */
    private $nbApplications = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Kev\PlatformBundle\Entity\AdvertSkill", mappedBy="advert")
     */
    private $advertSkills;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->categories = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->advertSkills = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param Image $image
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }
    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param Category $categories
     * @return Advert
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add applications
     *
     * @param \Kev\PlatformBundle\Entity\Application $application
     * @return Advert
     */
    public function addApplication(Application $application)
    {
        $this->application[] = $application;
        $application->setAdvert($this);
        return $this;
    }

    /**
     * Remove applications
     *
     * @param \Kev\PlatformBundle\Entity\Application $applications
     */
    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Callback for updatedAt
     * @ORM\PreUpdate
     */
    public function updatedDate()
    {
        $this->setUpdatedAt(new \DateTime());
    }


    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer 
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    public function increaseApplications()
    {
        $this->nbApplications++;
    }

    public function decreaseApplications()
    {
        $this->nbApplications--;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add advertSkills
     *
     * @param AdvertSkill $advertSkill
     * @return Advert
     */
    public function addAdvertSkill(AdvertSkill $advertSkill)
    {
        $this->advertSkills[] = $advertSkill;
        $advertSkill->setAdvert($this);
        return $this;
    }

    /**
     * Remove advertSkills
     *
     * @param AdvertSkill $advertSkill
     */
    public function removeAdvertSkill(AdvertSkill $advertSkill)
    {
        $this->advertSkills->removeElement($advertSkill);
    }

    /**
     * Get advertSkills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertSkills()
    {
        return $this->advertSkills;
    }
}
