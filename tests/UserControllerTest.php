<?php
use PHPUnit\Framework\TestCase; 
use eftec\bladeone\BladeOne;

class UserControllerTest extends TestCase { 
    private $controller; 
    private $bladeMock;
    private $userMock; protected function setUp(): void {
    $this->bladeMock = $this->createMock(BladeOne::class);
    $this->userMock = $this->createMock(User::class);
    User::method('getAuthenticatedUser')->willReturn($this->userMock);

    // Initialize the controller with the mocked BladeOne.
    $this->controller = new UserController($this->bladeMock);
    }

    public function testCreateArticleWithEmptyTitleOrContent()
    {
    // Simulate POST data
    $_POST['title'] = '';
    $_POST['content'] = 'Sample content';

    // Expect the Blade renderer to be called once with the error message
    $this->bladeMock->expects($this->once())
    ->method('run')
    ->with(
    $this->equalTo('create-article'),
    $this->equalTo(['error' => 'Title and content are required'])
    );

    $this->controller->createArticle();
    }

    public function testCreateArticleWithContentTooLong()
    {
    $_POST['title'] = 'Valid Title';
    $_POST['content'] = str_repeat('a', 1001); // 1001 characters long

    $this->bladeMock->expects($this->once())
    ->method('run')
    ->with(
    $this->equalTo('create-article'),
    $this->equalTo(['error' => 'Content is too long'])
    );

    $this->controller->createArticle();
    }

    public function testCreateArticleSuccessfully()
    {
    $_POST['title'] = 'Valid Title';
    $_POST['content'] = 'Valid content';

    // Expect the article creation method to be called once.
    $this->userMock->expects($this->once())
    ->method('createArticle')
    ->with(
    $this->equalTo('Valid Title'),
    $this->equalTo('Valid content')
    );

    $this->controller->createArticle();
    }
    }