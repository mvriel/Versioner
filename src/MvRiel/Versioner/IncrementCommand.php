<?php

namespace MvRiel\Versioner;

use Herrera\Version\Builder;
use Herrera\Version\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IncrementCommand extends Command
{
    const PART_MAJOR = 'major';
    const PART_MINOR = 'minor';
    const PART_PATCH = 'patch';

    protected function configure()
    {
        $this
            ->setName('increment')
            ->setDescription('Increments the current version number of the application')
            ->addOption(
                'part',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Defines which part needs to be updated, must be "'
                . self::PART_MAJOR . '", "' . self::PART_MINOR . '" or "' . self::PART_PATCH . '"',
                self::PART_MINOR
            )
            ->addOption(
                'pre-release',
                null,
                InputOption::VALUE_OPTIONAL,
                'Defines if this is a pre-release and what type (i.e. DEV, RC1, RC2, etc)'
            )
            ->addArgument('filename', InputArgument::REQUIRED, 'The file containing the version number to update');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $versionNumber = file_get_contents($filename);
        $version = Builder::create()->importString($versionNumber);

        $part = $input->getOption('part');
        switch (strtolower($part)) {
            case self::PART_MAJOR:
                $version->incrementMajor();
                $version->clearPreRelease();
                $version->clearBuild();
                break;
            case self::PART_PATCH:
                $version->incrementPatch();
                $version->clearPreRelease();
                $version->clearBuild();
                break;
            case self::PART_MINOR:
                $version->incrementMinor();
                $version->clearPreRelease();
                $version->clearBuild();
                break;
            default:
                throw new \InvalidArgumentException(
                    'Invalid name for the version part was provided, received: ' . $part
                );
                break;
        }

        $preRelease = $input->getOption('pre-release');
        if ($preRelease) {
            $version->setPreRelease(array($preRelease));
        }

        $newVersionNumber = Dumper::toString($version);
        file_put_contents($filename, $newVersionNumber);

        $output->writeln(
            "<info>The version number was incremented from <comment>$versionNumber</comment> to "
            . "<comment>$newVersionNumber</comment></info>"
        );
    }
}
