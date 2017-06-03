<?php

/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 12/03/17
 * Time: 00:40
 */
namespace StarterKit\SecurityManagerBundle\Doctrine;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use StarterKit\SecurityManagerBundle\Entity\User;
use StarterKit\SecurityManagerBundle\Entity\UserAccountInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


class HashPasswordListener implements EventSubscriber {

    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     * @param $passwordEncoder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function getSubscribedEvents() {
        return ['prePersist', 'preUpdate'];
    }
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof UserAccountInterface) {
            return;
        }
        $this->encodePassword($entity);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof UserAccountInterface) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param $user
     */
    private function encodePassword(UserAccountInterface $entity) {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }


}