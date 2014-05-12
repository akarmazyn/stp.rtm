<?php
namespace DashboardTest\Model;

use DashboardTest\Bootstrap;

class JenkinsDaoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing proper Jenkins API method - should return JSON parsed into array
     */
    public function testProperApiUrl()
    {
        /**
         * @var \Dashboard\Model\Dao\JenkinsDao
         */
        $jenkinsDao = Bootstrap::getServiceManager()->get('JenkinsDao');
        $testAdapter = new \Zend\Http\Client\Adapter\Test();
        $testAdapter->setResponse(
            file_get_contents(__DIR__ . '/../Mock/mockedJenkinsResponse.txt')
        );
        $jenkinsDao->getDataProvider()->setAdapter($testAdapter);

        $response = $jenkinsDao->fetchStatusForBuildWidget(array(
            'view' => 'VGTV',
            'job' => 'VGTV_front-end',
        ));

        $this->assertTrue(is_array($response), 'Testing proper API URL');
    }

    /**
     * Executing fetch* method that is not defined in JenkinsDao - should throw an Exception
     * @expectedException \Dashboard\Model\Dao\Exception\FetchNotImplemented
     */
    public function testImproperApiMethod()
    {
        Bootstrap::getServiceManager()->get('JenkinsDao')->fetchImproperDataName(array(
            'view' => 'VGTV',
            'job' => 'VGTV_front-end',
        ));
    }

    /**
     * Proper API method, not all required params given - should throw an Exception
     * @expectedException \Dashboard\Model\Dao\Exception\EndpointUrlNotAssembled
     */
    public function testNotAllRequiredParamsGiven()
    {
        Bootstrap::getServiceManager()->get('JenkinsDao')->fetchStatusForBuildWidget(array(
            'view' => 'VGTV',
        ));
    }
}
