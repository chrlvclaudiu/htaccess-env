<?php


namespace DreamProduction\Composer;

use Composer\Composer;
use Composer\Util\ProcessExecutor;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Installer\PackageEvents;
use Composer\Script\ScriptEvents;
use Composer\Installer\PackageEvent;
use Composer\Script\Event;


class Htaccess implements PluginInterface, EventSubscriberInterface {
  
  /**
   * @var Composer $composer
   */
  protected $composer;
  /**
   * @var IOInterface $io
   */
  protected $io;
  /**
   * @var EventDispatcher $eventDispatcher
   */
  protected $eventDispatcher;
  /**
   * @var ProcessExecutor $executor
   */
  protected $executor;

  public function activate(Composer $composer, IOInterface $io) {
      $this->composer = $composer;
      $this->io = $io;
      $this->eventDispatcher = $composer->getEventDispatcher();
      $this->executor = new ProcessExecutor($this->io);
    }

  public static function getSubscribedEvents() {
      return array(
        PackageEvents::POST_PACKAGE_INSTALL => 'postPackage',
        PackageEvents::POST_PACKAGE_UPDATE => 'postPackage',
        //PackageEvents::POST_PACKAGE_UNINSTALL => 'postPackage',
        //ScriptEvents::POST_INSTALL_CMD => 'postCmd',
        ScriptEvents::POST_UPDATE_CMD => 'postCmd'
      );
  }

  /**
   * Post package event behaviour.
   *
   * @param \Composer\Installer\PackageEvent $event
   */
  protected function postPackage(PackageEvent $event) {

  }

  /**
   * Post command event callback.
   *
   * @param \Composer\Script\Event $event
   */
  protected function postCmd(Event $event) {

  }
  
  protected function getDrupalStableVersion() {
    try {
      // Load the HTML from Drupal.org
      $html =  HtmlDomParser::file_get_html('https://www.drupal.org/project/drupal');
      
      // Find the first release row (assume it's Drupal 8, as being the most important now)
      if ($release_div = $html->find('div.pane-project-downloads-recommended div.view-content div.views-row-1 div.views-field-field-release-version div.field-content')) {
        // Get the release text title
        $release_h4_text = $release_div[0]->find('h4', 0)->innertext;
        $latestStableVersion = trim(str_replace(Version::DRUPAL_CORE_PREFIX_TEXT, '', $release_h4_text)) . '.0'; // Append .0 to fully compare with installed package that have 4 digits in the version
        
        $this->io->write('<info>Latest stable Drupal version: <warning>' . $latestStableVersion . '</warning></info>');
        
        // Get installed version of the package
        $current_drupal_version = trim(strtolower($this->getInstalledDrupalVersion()));
        
        // Compare versions
        if (version_compare(strtolower($latestStableVersion), $current_drupal_version) == 0 ) {
          $this->io->write('<info>You are up-to-date with your Drupal version.</info>');
        }
        else {
          $this->io->write('<error>You are not running the latest stabled version. Currently installed version is ' . $current_drupal_version . '</error>');
        }
        
        
        return $release_h4_text;
      }
      else {
        $this->io->write('<error>No release found :( Please check the plugin and inform the author.</error>');
      }
      
      return NULL;
    }
    catch (Exception $ex) {
      $this->io->write('<error>Unhandled exception: ' . $ex->getMessage() . '</error>');
    }
  }
}