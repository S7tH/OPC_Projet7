<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;

class CustomerAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('customer:add')
            ->setDescription('Add a new Bilemo customer')
            ->addOption(
                'email',
                null,
                InputOption::VALUE_REQUIRED,
                'Sets facebook email.',
                null
            )
            ->addOption(
                'super_admin',
                null,
                InputOption::VALUE_NONE,
                'Sets admin role.',
                null
            )                        
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info> command creates a new Bilemo customer with a facebook email.
                    php bin/console customer:add --email=customer.mail@customerfacebookadress.com.
                    To create an admin : 
                    php bin/console customer:add --email=customer.mail@customerfacebookadress.com. --super_admin
                    
 
<info>php %command.full_name% [--email=...] [--super_admin] name</info>
 
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output
            ->writeln('saving the customer email...');
        
        //recover the doctrine service and launch it
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $email = $input->getOption('email');

        //create a user instance to save the customer
        $customer = new User(null, null, $email, null);

        if($input->getOption('super_admin'))
        {
            $customer->setRoles(['ROLE_ADMIN']);
        }
        else
        {
            $customer->setRoles(['ROLE_USER']);
        }

        $em->persist($customer);
        $em->flush();

        $output->writeln('Customer has been saved.');
    }

}