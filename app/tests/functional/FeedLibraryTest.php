<?php

use Cottonwood\Feed\Document\AtomDocument;
use Cottonwood\Feed\Document\RssDocument;

class FeedLibraryTest extends TestCase
{
    public function testCreateFromFile()
    {
        $feedDocument = FeedBuilder::createFromFile(__DIR__ . '/support/ExampleRss.xml');
        
        $this->assertInstanceOf('Cottonwood\Feed\Document\FeedDocument', $feedDocument, 'Feed::createFromFile() should return an instance of FeedDocument');
    }
    
    /**
     * @expectedException Cottonwood\Support\Exceptions\FileReadException
     * @expectedExceptionMessage Could not open resource.
     */
    public function testCreateFromFileInvalidLocation()
    {
        $feedDocument = FeedBuilder::createFromFile('./somewhere/that/does/not/exist.xml');
    }
    
    public function testCreateFromFileRssDocument()
    {
        $feedDocument = FeedBuilder::createFromFile(__DIR__ . '/support/ExampleRss.xml');
        
        $this->assertInstanceOf('Cottonwood\Feed\Document\RssDocument', $feedDocument, 'Feed::createFromFile() should return and instance of RssDocument when parsing an Rss Feed');
        
        return $feedDocument;
    }
    
    public function testCreateFromFileAtomDocument()
    {
        $feedDocument = FeedBuilder::createFromFile(__DIR__ . '/support/ExampleAtom.xml');
        
        $this->assertInstanceOf('Cottonwood\Feed\Document\AtomDocument', $feedDocument, 'Feed::createFromFile() should return and instance of AtomDocument when parsing an Atom Feed');
        
        return $feedDocument;
    }
    
    /**
     * @depends testCreateFromFileRssDocument
     */
    public function testRssDocumentProperties(RssDocument $document)
    {
        $this->assertInternalType('string', $document->getTitle(), 'The Feed\'s title should be of type string.');
        $this->assertEquals('Example RSS', $document->getTitle(), 'The Feed\'s title should match the value in the test document');
        
        $this->assertInternalType('int', $document->getTimestamp(), 'The Feed\'s timestamp should be of type int.');
        $this->assertEquals(1110867488, $document->getTimestamp(), 'The Feed\'s timestamp should match the value in the test document');
        
        $this->assertInternalType('string', $document->getPublishDate(), 'The Feed\'s publish date should be of type string.');
        $this->assertEquals('Tue, 15 Mar 2005 05:18 pm', $document->getPublishDate(), 'The Feed\'s publish date should match the value in the test document');
    }
    
    /**
     * @depends testCreateFromFileAtomDocument
     */
    public function testAtomDocumentProperties(AtomDocument $document)
    {
        
        $this->assertInternalType('string', $document->getTitle(), 'The Feed\'s title should be of type string.');
        $this->assertEquals('Example Atom', $document->getTitle(), 'The Feed\'s title should match the value in the test document');
        
        $this->assertInternalType('int', $document->getTimestamp(), 'The Feed\'s timestamp should be of type int.');
        $this->assertEquals(1157967333, $document->getTimestamp(), 'The Feed\'s timestamp should match the value in the test document');
        
        $this->assertInternalType('string', $document->getPublishDate(), 'The Feed\'s publish date should be of type string.');
        $this->assertEquals('Mon, 11 Sep 2006 02:35 am', $document->getPublishDate(), 'The Feed\'s publish date should match the value in the test document');
    }
    
    /**
     * @depends testCreateFromFileRssDocument
     */
    public function testRssDocumentArticles(RssDocument $document)
    {
        $articles = $document->getArticles();
        
        $this->assertInternalType('array', $document->getArticles(), 'The getArticles() method should return an array.');
        
        $article = $articles[0];
        
        $this->assertInstanceOf('Cottonwood\Feed\Support\Article', $article, 'The array returned by getArticles() should contain instances of Article.');
        
        $this->assertInternalType('string', $article->getTitle(), 'The Article\'s title should be of type string.');
        $this->assertEquals('My First Entry', $article->getTitle(), 'The Article\'s title should match the value in the test document');
        
        $this->assertInternalType('string', $article->getLink(), 'The Article\'s link should be of type string.');
        $this->assertEquals('http://example.com/first.html', $article->getLink(), 'The Article\'s link should match the value in the test document');
        
        $this->assertInternalType('string', $article->getSummary(), 'The Article\'s summary should be of type string');
        $this->assertEquals("<p>He often used to say there was only one Road; that it was like a great river: its springs were at every doorstep and every path was its tributary. \"It's a dangerous business, Frodo, going out of your door,\" he used to say. \"You step into the Road, and if you don't keep your feet, there is no telling where you might be swept off to.\"</p>", $article->getSummary(), 'The Article\'s summary should match the value in the test document.');
        
        $this->assertInternalType('boolean', $article->hasContent(), 'The hasContent() method should return an boolean value.');
        $this->assertInternalType('string', $article->getContent(), 'The Article\'s content should be of type string');
        $this->assertXmlStringEqualsXmlString("<div><p>He often used to say there was only one Road; that it was like a great river: its springs were at every doorstep and every path was its tributary. \"It's a dangerous business, Frodo, going out of your door,\" he used to say. \"You step into the Road, and if you don't keep your feet, there is no telling where you might be swept off to.\"</p></div>", $article->getContent(), 'The Article\'s content should match the value in the test document.');
        
        $this->assertInternalType('string', $article->getPublishDate(), 'The Article\'s publish date should be of type string.');
        $this->assertEquals('Tue, 15 Mar 2005 05:18 pm', $article->getPublishDate(), 'The Article\'s publish date should match the value in the test document');
        
        $this->assertInternalType('int', $article->getTimestamp(), 'The Article\'s timestamp should be of type int.');
        $this->assertEquals(1110867488, $article->getTimestamp(), 'The Article\'s timestamp should match the value in the test document');
        
        $this->assertInternalType('string', $article->getHash(), 'The Article\'s hash should be of type string.');
        $this->assertEquals('e41cfaa6acb54f1bf199a24607e2c6e0', $article->getHash(), 'The Article\'s hash should match the value of the test document');
        $this->assertFalse($article->compareHash('NotTheHash!'), 'The compareHash() method should return false when a non-matching value is provided.');
        $this->assertTrue($article->compareHash($article->getHash()), 'The compareHash() method should return true when a matching value is provided.');
        
        $this->assertInternalType('array', $article->toArray(), 'The toArray() method should return a value of type array.');
        $this->assertInternalType('string', $article->toJSON(), 'The toJSON() method should return a value of type string.');
    }
    
    /**
     * @depends testCreateFromFileAtomDocument
     */
    public function testAtomDocumentArticles(AtomDocument $document)
    {
        $articles = $document->getArticles();
        
        $this->assertInternalType('array', $document->getArticles(), 'The getArticles() method should return an array.');
        
        $article = $articles[0];
        
        $this->assertInstanceOf('Cottonwood\Feed\Support\Article', $article, 'The array returned by getArticles() should contain instances of Article.');
        
        $this->assertInternalType('string', $article->getTitle(), 'The Article\'s title should be of type string.');
        $this->assertEquals('My First Entry', $article->getTitle(), 'The Article\'s title should match the value in the test document');
        
        $this->assertInternalType('string', $article->getLink(), 'The Article\'s link should be of type string.');
        $this->assertEquals('http://example.com/first.html', $article->getLink(), 'The Article\'s link should match the value in the test document');
        
        $this->assertInternalType('string', $article->getSummary(), 'The Article\'s summary should be of type string');
        $this->assertEquals("<p>He often used to say there was only one Road; that it was like a great river: its springs were at every doorstep and every path was its tributary. \"It's a dangerous business, Frodo, going out of your door,\" he used to say. \"You step into the Road, and if you don't keep your feet, there is no telling where you might be swept off to.\"</p>", $article->getSummary(), 'The Article\'s summary should match the value in the test document.');
        
        $this->assertInternalType('boolean', $article->hasContent(), 'The hasContent() method should return an boolean value.');
        $this->assertInternalType('string', $article->getContent(), 'The Article\'s content should be of type string');
        $this->assertXmlStringEqualsXmlString("<div><p>He often used to say there was only one Road; that it was like a great river: its springs were at every doorstep and every path was its tributary. \"It's a dangerous business, Frodo, going out of your door,\" he used to say. \"You step into the Road, and if you don't keep your feet, there is no telling where you might be swept off to.\"</p></div>", $article->getContent(), 'The Article\'s content should match the value in the test document.');
        
        $this->assertInternalType('string', $article->getPublishDate(), 'The Article\'s publish date should be of type string.');
        $this->assertEquals('Mon, 11 Sep 2006 02:35 am', $article->getPublishDate(), 'The Article\'s publish date should match the value in the test document');
        
        $this->assertInternalType('int', $article->getTimestamp(), 'The Article\'s timestamp should be of type int.');
        $this->assertEquals(1157967333, $article->getTimestamp(), 'The Article\'s timestamp should match the value in the test document');
        
        $this->assertInternalType('string', $article->getHash(), 'The Article\'s hash should be of type string.');
        $this->assertEquals('8c5e09bad62978919b2ec9afc17d3309', $article->getHash(), 'The Article\'s hash should match the value of the test document');
        $this->assertFalse($article->compareHash('NotTheHash!'), 'The compareHash() method should return false when a non-matching value is provided.');
        $this->assertTrue($article->compareHash($article->getHash()), 'The compareHash() method should return true when a matching value is provided.');
        
        $this->assertInternalType('array', $article->toArray(), 'The toArray() method should return a value of type array.');
        $this->assertInternalType('string', $article->toJSON(), 'The toJSON() method should return a value of type string.');
    }
}






