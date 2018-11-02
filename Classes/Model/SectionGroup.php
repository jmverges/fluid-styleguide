<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 16:42
 */

namespace Pluswerk\FluidStyleguide\Model;


use Pluswerk\FluidStyleguide\Registry\SectionGroupRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class SectionGroup
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Section[]
     */
    private $sections;

    /**
     * @var SectionGroup[]
     */
    private $sectionGroups;

    /**
     * @var SectionGroup
     */
    private $parentSectionGroup;

    /**
     * @var array
     */
    private $path;

    /**
     * SectionGroup constructor.
     *
     * @param array $path
     * @param ObjectManager|null $objectManager
     */
    public function __construct(array $path, ObjectManager $objectManager = null)
    {
        $this->identifier = implode('__', $path);
        array_pop($path);
        $this->path = $path;
        $this->sections = new ObjectStorage();
        $this->sectionGroups = new ObjectStorage();
        /** @var ObjectManager $objectManager */
        $objectManager = $objectManager ?? GeneralUtility::makeInstance(ObjectManager::class);
        /** @var SectionGroupRegistry $sectionGroupRegistry */
        $sectionGroupRegistry = $objectManager->get(SectionGroupRegistry::class);
        $sectionGroupRegistry->addSectionGroup($this);
    }

    /**
     * @param Section $section
     */
    public function addSection(Section $section): void
    {
        $this->sections[$section->getIdentifier()] = $section;
        ksort($this->sections);
    }

    /**
     * @param SectionGroup $sectionGroup
     */
    public function addSectionGroup(SectionGroup $sectionGroup): void
    {
        $sectionGroup->setParentSectionGroup($this);
        $this->sectionGroups[$sectionGroup->getIdentifier()] = $sectionGroup;
        ksort($this->sectionGroups);
    }

    /**
     * @return ObjectStorage
     */
    public function getSections(): ObjectStorage
    {
        return $this->sections;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return ObjectStorage
     */
    public function getSectionGroups(): ObjectStorage
    {
        return $this->sectionGroups;
    }

    /**
     * @return SectionGroup
     */
    public function getParentSectionGroup(): SectionGroup
    {
        return $this->parentSectionGroup;
    }

    /**
     * @param SectionGroup $parentSectionGroup
     */
    public function setParentSectionGroup(SectionGroup $parentSectionGroup): void
    {
        $this->parentSectionGroup = $parentSectionGroup;
    }

    /**
     * @return bool
     */
    public function isRootSectionGroup(): bool
    {
        return $this->parentSectionGroup === null;
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}