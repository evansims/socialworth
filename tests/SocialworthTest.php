<?php
namespace Tests\Socialworth;

use Evansims\Socialworth;

class SocialworthTest extends \PHPUnit_Framework_TestCase
{
    protected $test_url = 'http://digg.com';
    protected $test_bad_url = 'supercalifragilisticexpialidocious';
    protected $test_no_results_url = 'http://thisisbogus.supercalifragilisticexpialidocious.gov';
    protected $bogus_service_name = 'wubbalubbadubdubs';
    protected $bogus_array = array('yadda' => 'yadda');

    public function testConstructor()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertNotEmpty($instance, _("Constructor method did not return instance as expected."));
    }

    public function testSetURL()
    {
        $instance = new Socialworth();
        $this->assertNotEmpty($instance->url($this->test_url), _("URL method did not return instance as expected."));
    }

    public function testSetBadURL()
    {
        $instance = new Socialworth();

        try {
            $instance->url($this->test_bad_url);
            $instance->twitter();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail(_("Bad URL was accepted as valid."));
    }

    public function testGetter()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertNotEmpty($instance->twitter, _("Magic getter did not return expected response."));
    }

    public function testSetter()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertNotEmpty($instance->twitter(), _("Magic setter did not return expected response."));
    }

    public function testSetterChangeState()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertNotEmpty($instance->twitter(true), _("Attempt to change query toggle using magic setter failed."));
    }

    public function testStaticBadURL()
    {
        try {
            Socialworth::twitter();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail(_("Static service method accepted a null URL as valid."));
    }

    public function testCallWithURL()
    {
        $instance = new Socialworth();
        $this->assertNotEmpty($instance->twitter($this->test_url), _("Attempt to assign URL with service call failed."));
    }

    public function testIsSetService()
    {
        $instance = new Socialworth();
        $this->assertNotEmpty(isset($instance->twitter), _("Attempt to check service call state using isset() failed."));
    }

    public function testSetService()
    {
        $instance = new Socialworth();
        unset($instance->twitter);
        $this->assertEmpty(isset($instance->twitter), _("Attempt to disable service call state using __set() magic method failed."));
        $instance->twitter = true;
        $this->assertNotEmpty(isset($instance->twitter), _("Attempt to enable service call state using __set() magic method failed."));
    }

    public function testSetServiceBogusService()
    {
        $bogus_service_name = $this->bogus_service_name;

        $instance = new Socialworth();
        $instance->$bogus_service_name = true;
        $this->assertEmpty(isset($instance->$bogus_service_name), _("Attempt to enable bogus service call succeeded."));
        unset($instance->$bogus_service_name);
        $this->assertEmpty(isset($instance->$bogus_service_name), _("Attempt to disable bogus service call using unset() failed."));
    }

    public function testUnsetService()
    {
        $instance = new Socialworth();
        unset($instance->twitter);
        $this->assertEmpty(isset($instance->twitter), _("Attempt to disable service call using unset() failed."));
    }

    public function testGetBogusService()
    {
        $bogus_service_name = $this->bogus_service_name;
        $bogus_array = $this->bogus_array;
        $dummy = null;

        $instance = new Socialworth();
        try {
            $dummy = $instance->$bogus_service_name;
            $this->fail(_('Bogus service was allowed by magic __get() method.'));
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }

        try {
            $dummy = $instance->__get($bogus_array);
            $this->fail(_('Bogus array was allowed by magic __get() method.'));
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    public function testNoUrl()
    {
        $dummy = null;
        $instance = new Socialworth();

        try {
            $dummy = $instance->twitter();
            $this->fail(_('An API query was performed without a target URL assigned.'));
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    public function testTwitter()
    {
        $this->assertNotEmpty(Socialworth::twitter($this->test_url), _("Twitter test did not return expected response."));
    }

    public function testTwitterBogus()
    {
        $this->assertEmpty(Socialworth::twitter($this->test_no_results_url), _("Twitter bogus url test did not return expected response."));
    }

    public function testFacebook()
    {
        $this->assertNotEmpty(Socialworth::facebook($this->test_url), _("Facebook test did not return expected response."));
    }

    public function testFacebookBogus()
    {
        $this->assertEmpty(Socialworth::facebook($this->test_no_results_url), _("Facebook bogus url test did not return expected response."));
    }

    public function testPinterest()
    {
        $this->assertNotEmpty(Socialworth::pinterest($this->test_url), _("Pinterest test did not return expected response."));
    }

    public function testPinterestBogus()
    {
        $this->assertEmpty(Socialworth::pinterest($this->test_no_results_url), _("Pinterest bogus url test did not return expected response."));
    }

    public function testReddit()
    {
        $this->assertNotEmpty(Socialworth::reddit($this->test_url), _("Reddit test did not return expected response."));
    }

    public function testRedditBogus()
    {
        $this->assertEmpty(Socialworth::reddit($this->test_no_results_url), _("Reddit bogus url test did not return expected response."));
    }

    public function testHackerNews()
    {
        $this->markTestSkipped(_("Hacker News API endpoint is offline."));
        //$this->assertNotEmpty(Socialworth::hackernews($this->test_url), _("Hacker News test did not return expected response."));
    }

    public function testHackerNewsBogus()
    {
        $this->markTestSkipped(_("Hacker News API endpoint is offline."));
        //$this->assertEmpty(Socialworth::hackernews($this->test_no_results_url), _("Hacker News bogus url test did not return expected response."));
    }

    public function testGooglePlus()
    {
        $this->assertNotEmpty(Socialworth::googleplus($this->test_url), _("Google+ test did not return expected response."));
    }

    public function testGooglePlusBogus()
    {
        $this->assertEmpty(Socialworth::googleplus($this->test_no_results_url), _("Google+ bogus url test did not return expected response."));
    }

    public function testStumbleUpon()
    {
        $this->assertNotEmpty(Socialworth::stumbleupon($this->test_url), _("StumbleUpon test did not return expected response."));
    }

    public function testStumbleUponBogus()
    {
        $this->assertEmpty(Socialworth::stumbleupon($this->test_no_results_url), _("StumbleUpon bogus url test did not return expected response."));
    }

    public function testLinkedIn()
    {
        $this->assertNotEmpty(Socialworth::linkedin($this->test_url), _("LinkedIn test did not return expected response."));
    }

    public function testLinkedInBogus()
    {
        $this->assertEmpty(Socialworth::linkedin($this->test_no_results_url), _("LinkedIn bogus url test did not return expected response."));
    }

    public function testBogusEndpoint()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertEmpty($instance->testcase(), _("Bogus service query returned a response. That shouldn't happen."));
    }

    public function testAll()
    {
        $instance = new Socialworth($this->test_url);
        $this->assertNotEmpty($instance->all(), _("All services query did not return expected response."));
    }

    public function testAllBogus()
    {
        $instance = new Socialworth($this->test_no_results_url);
        $response = $instance->all();
        $this->assertEmpty($response->total, _("All services query did not return expected response."));
    }
}
