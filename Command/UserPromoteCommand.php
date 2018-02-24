<?php
/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 10/07/17
 * Time: 20:32
 */

namespace EscapeHither\SecurityManagerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\Question;

class UserPromoteCommand extends ContainerAwareCommand {
    protected $type;
    const EMAIL = 'mail';
    const ROLE = 'role';
    const USERNAME = 'username';

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('escapehither:securitymanager:user-promote')
            // the short description shown while running "php bin/console list"
            ->setDescription('Add a Role to a user.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to add new role to a user...')//->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);
        $inputList = [
            self::USERNAME,
            self::EMAIL,
            self::ROLE,
        ];
        $roles = array_keys($this->getContainer()->getParameter('security.role_hierarchy.roles'));


        $helper = $this->getHelper('question');
        $data = [];
        foreach ($inputList as $value) {
            $this->setType($value);
            if($value != self::ROLE){
                $data[$value] = $this->askInput($input, $output, $helper);
            }else{
                $output->writeln([
                  'Please choose one of this role',
                  '',
                ]);
                foreach ($roles as $key=>$role){
                    $output->writeln([sprintf(' %s role: %s',$key,$role),'']);
                }
                $roleKey = $this->askInput($input, $output, $helper);
                if (!array_key_exists ($roleKey,$roles)) {
                    throw new \Exception('Undefined role in role hierarchy ');
                }
                $data[$value] = $roles[$roleKey];
            }

        }

        $this->userPromote($data);
        // outputs a message followed by a "\n"
        $output->writeln('The user : ' .$data[self::USERNAME].' successfully update with the role'.$data[self::ROLE]);

    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param                                                   $helper
     * @param null                                              $message
     * @return mixed
     */
    protected function askInput(InputInterface $input, OutputInterface $output, $helper, $message = NULL) {
        if (!empty($message)) {
            $ask = $message;
        }
        else {
            $ask = 'Please enter the ' . $this->type . ' : ';
        }
        $question = new Question($ask);

        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The ' . $this->type . ' cannot be empty');
            }
            return $value;
        });
        $question->setMaxAttempts(3);
        return $helper->ask($input, $output, $question);

    }

    /**
     * @param mixed $type
     */
    protected function setType($type) {
        $this->type = $type;
    }

    protected function userPromote($data) {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $user_provider_class = $container->getParameter('escape_hither.security.user.class');
        $repository = $em->getRepository($user_provider_class);
        $user = $repository->findOneByEmail($data[self::EMAIL]);
        if (!$user) {
            throw new \Exception('The  user is not found');
        }else{
            $roles = $user->getRoles();
            if (in_array($data[self::ROLE], $roles)) {
                throw new \Exception('The role already for the user '.$data[self::EMAIL]);
            }
            $roles[] = $data[self::ROLE];
            if (!empty($roles)) {
                $user->setRoles($roles);
            }
        }


        $em->persist($user);
        $em->flush();
    }


}