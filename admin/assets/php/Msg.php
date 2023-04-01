<?php

//FlashMessages
class Msg
{

    // Message types and shortcuts
    const INFO = 'i';
    const SUCCESS = 's';
    const WARNING = 'w';
    const ERROR = 'e';

    // Default message type
    const defaultType = self::INFO;

    // Message types and order
    // 
    // Note:  The order that message types are listed here is the same order 
    // they will be printed on the screen (ie: when displaying all messages)
    // 
    // This can be overridden with the display() method by manually defining
    // the message types in the order you want to display them. For example:
    // 
    // $msg->display([$msg::SUCCESS, $msg::INFO, $msg::ERROR, $msg::WARNING])
    // 
    protected $msgTypes = [
        self::ERROR => 'error',
        self::WARNING => 'warning',
        self::SUCCESS => 'success',
        self::INFO => 'info',
    ];

    // Each message gets wrapped in this
    protected $msgWrapper = "<div class='%s'>%s</div>\n";

    // Prepend and append to each message (inside of the wrapper)
    protected $msgBefore = '';
    protected $msgAfter = '';

    // HTML for the close button
    protected $closeBtn = '<button type="button" class="close" 
                                data-dismiss="alert" 
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';

    // CSS Classes
    protected $stickyCssClass = 'sticky';
    protected $msgCssClass = 'alert dismissable';
    protected $cssClassMap = [
        self::INFO => 'alert-info',
        self::SUCCESS => 'alert-success',
        self::WARNING => 'alert-warning',
        self::ERROR => 'alert-danger',
    ];

    // Where to redirect the user after a message is queued
    protected $redirectUrl = null;

    // The unique ID for the session/messages (do not edit)
    protected $msgId;


    /**
     * __construct
     *
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a unique ID for this user and session
        $this->msgId = sha1(uniqid());

        // Create session array to hold our messages if it doesn't already exist
        if (!array_key_exists('flash_messages', $_SESSION)) $_SESSION['flash_messages'] = [];

    }

    public function show()
    {
        if ($this->hasMessages()) {
            return $this->display();
        }
    }

    /**
     * See if there are any queued message
     *
     * @param string $type The $msgType
     * @return boolean
     *
     */
    public function hasMessages($type = null)
    {
        if (!is_null($type)) {
            if (!empty($_SESSION['flash_messages'][$type])) return $_SESSION['flash_messages'][$type];
        } else {
            foreach (array_keys($this->msgTypes) as $type) {
                if (isset($_SESSION['flash_messages'][$type]) && !empty($_SESSION['flash_messages'][$type])) return $_SESSION['flash_messages'][$type];
            }
        }
        return false;
    }

    /**
     * Display the flash messages
     *
     * @param mixed $types (null)  print all of the message types
     *                          (array)  print the given message types
     *                          (string)   print a single message type
     * @param boolean $print Whether to print the data or return it
     * @return string
     *
     */
    public function display($types = null, $print = true)
    {

        if (!isset($_SESSION['flash_messages'])) return false;

        $output = '';

        // Print all the message types
        if (is_null($types) || !$types || (is_array($types) && empty($types))) {
            $types = array_keys($this->msgTypes);

            // Print multiple message types (as defined by an array)
        } elseif (is_array($types) && !empty($types)) {
            $theTypes = $types;
            $types = [];
            foreach ($theTypes as $type) {
                $types[] = strtolower($type[0]);
            }

            // Print only a single message type
        } else {
            $types = [strtolower($types[0])];
        }


        // Retrieve and format the messages, then remove them from session data
        foreach ($types as $type) {
            if (!isset($_SESSION['flash_messages'][$type]) || empty($_SESSION['flash_messages'][$type])) continue;
            foreach ($_SESSION['flash_messages'][$type] as $msgData) {
                $output .= $this->formatMessage($msgData, $type);
            }
            $this->clear($type);
        }


        // Print everything to the screen (or return the data)
        if ($print) {
            echo $output;
        } else {
            return $output;
        }
    }

    /**
     * Format a message
     *
     * @param array $msgDataArray Array of message data
     * @param string $type The $msgType
     * @return string                 The formatted message
     *
     */
    protected function formatMessage($msgDataArray, $type)
    {

        $msgType = isset($this->msgTypes[$type]) ? $type : $this->defaultType;
        $cssClass = $this->msgCssClass . ' ' . $this->cssClassMap[$type];
        $msgBefore = $this->msgBefore;

        // If sticky then append the sticky CSS class
        if ($msgDataArray['sticky']) {
            $cssClass .= ' ' . $this->stickyCssClass;

            // If it's not sticky then add the close button
        } else {
            $msgBefore = $this->closeBtn . $msgBefore;
        }

        // Wrap the message if necessary
        $formattedMessage = $msgBefore . $msgDataArray['message'] . $this->msgAfter;

        return sprintf(
            $this->msgWrapper,
            $cssClass,
            $formattedMessage
        );
    }

    /**
     * Clear the messages from the session data
     *
     * @param mixed $types (array) Clear all of the message types in array
     *                        (string)  Only clear the one given message type
     * @return object
     *
     */
    protected function clear($types = [])
    {
        if ((is_array($types) && empty($types)) || is_null($types) || !$types) {
            unset($_SESSION['flash_messages']);
        } elseif (!is_array($types)) {
            $types = [$types];
        }

        foreach ($types as $type) {
            unset($_SESSION['flash_messages'][$type]);
        }

        return $this;
    }

    /**
     * Add an info message
     *
     * @param string $message The message text
     * @param string $redirectUrl Where to redirect once the message is added
     * @param boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function info($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::INFO, $redirectUrl, $sticky);
    }

    /**
     * Add a flash message to the session data
     *
     * @param string $message The message text
     * @param string $type The $msgType
     * @param string $redirectUrl Where to redirect once the message is added
     * @param boolean $sticky Whether or not the message is stickied
     * @return object
     *
     */
    public function add($message, $type = self::defaultType, $redirectUrl = null, $sticky = false)
    {

        // Make sure a message and valid type was passed
        if (!isset($message[0])) return false;
        if (strlen(trim($type)) > 1) $type = strtolower($type[0]);
        if (!array_key_exists($type, $this->msgTypes)) $type = $this->defaultType;

        // Add the message to the session data
        if (!array_key_exists($type, $_SESSION['flash_messages'])) $_SESSION['flash_messages'][$type] = array();
        $_SESSION['flash_messages'][$type][] = ['sticky' => $sticky, 'message' => $message];

        // Handle the redirect if needed
        if (!is_null($redirectUrl)) $this->redirectUrl = $redirectUrl;
        $this->doRedirect();

        return $this;
    }

    /**
     * Redirect the user if a URL was given
     *
     * @return object
     *
     */
    protected function doRedirect()
    {
        if ($this->redirectUrl) {
            header('Location: ' . $this->redirectUrl);
            exit();
        }
        return $this;
    }

    /**
     * Add a success message
     *
     * @param string $message The message text
     * @param string $redirectUrl Where to redirect once the message is added
     * @param boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function success($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::SUCCESS, $redirectUrl, $sticky);
    }

    /**
     * Add a warning message
     *
     * @param string $message The message text
     * @param string $redirectUrl Where to redirect once the message is added
     * @param boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function warning($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::WARNING, $redirectUrl, $sticky);
    }

    /**
     * Add an error message
     *
     * @param string $message The message text
     * @param string $redirectUrl Where to redirect once the message is added
     * @param boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function error($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::ERROR, $redirectUrl, $sticky);
    }

    /**
     * Add a sticky message
     *
     * @param string $message The message text
     * @param string $redirectUrl Where to redirect once the message is added
     * @param string $type The $msgType
     * @return object
     *
     */
    public function sticky($message = true, $redirectUrl = null, $type = self::defaultType)
    {
        return $this->add($message, $type, $redirectUrl, true);
    }

    /**
     * See if there are any queued error messages
     *
     * @return boolean
     *
     */
    public function hasErrors()
    {
        return empty($_SESSION['flash_messages'][self::ERROR]) ? false : true;
    }

    /**
     * Set the HTML that each message is wrapped in
     *
     * @param string $msgWrapper The HTML that each message is wrapped in.
     *                           Note: Two placeholders (%s) are expected.
     *                           The first is the $msgCssClass,
     *                           The second is the message text.
     * @return object
     *
     */
    public function setMsgWrapper($msgWrapper = '')
    {
        $this->msgWrapper = $msgWrapper;
        return $this;
    }

    /**
     * Prepend string to the message (inside of the message wrapper)
     *
     * @param string $msgBefore string to prepend to the message
     * @return object
     *
     */
    public function setMsgBefore($msgBefore = '')
    {
        $this->msgBefore = $msgBefore;
        return $this;
    }

    /**
     * Append string to the message (inside of the message wrapper)
     *
     * @param string $msgAfter string to append to the message
     * @return object
     *
     */
    public function setMsgAfter($msgAfter = '')
    {
        $this->msgAfter = $msgAfter;
        return $this;
    }

    /**
     * Set the HTML for the close button
     *
     * @param string $closeBtn HTML to use for the close button
     * @return object
     *
     */
    public function setCloseBtn($closeBtn = '')
    {
        $this->closeBtn = $closeBtn;
        return $this;
    }

    /**
     * Set the CSS class for sticky notes
     *
     * @param string $stickyCssClass the CSS class to use for sticky messages
     * @return object
     *
     */
    public function setStickyCssClass($stickyCssClass = '')
    {
        $this->stickyCssClass = $stickyCssClass;
        return $this;
    }

    /**
     * Set the CSS class for messages
     *
     * @param string $msgCssClass The CSS class to use for messages
     *
     * @return object
     *
     */
    public function setMsgCssClass($msgCssClass = '')
    {
        $this->msgCssClass = $msgCssClass;
        return $this;
    }

    /**
     * Set the CSS classes for message types
     *
     * @param mixed $msgType (string) The message type
     *                           (array) key/value pairs for the class map
     * @param mixed $cssClass (string) the CSS class to use
     *                           (null) not used when $msgType is an array
     * @return object
     *
     */
    public function setCssClassMap($msgType, $cssClass = null)
    {

        if (!is_array($msgType)) {
            // Make sure there's a CSS class set
            if (is_null($cssClass)) return $this;
            $msgType = [$msgType => $cssClass];
        }

        foreach ($msgType as $type => $cssClass) {
            $this->cssClassMap[$type] = $cssClass;
        }

        return $this;
    }


}

?>
<body>
<style>
    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .alert-heading {
        color: inherit;
    }

    .alert-link {
        font-weight: 700;
    }

    .alert-dismissible {
        padding-right: 4rem;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }

    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }

    .alert-primary hr {
        border-top-color: #9fcdff;
    }

    .alert-primary .alert-link {
        color: #002752;
    }

    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }

    .alert-secondary hr {
        border-top-color: #c8cbcf;
    }

    .alert-secondary .alert-link {
        color: #202326;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-success hr {
        border-top-color: #b1dfbb;
    }

    .alert-success .alert-link {
        color: #0b2e13;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-info hr {
        border-top-color: #abdde5;
    }

    .alert-info .alert-link {
        color: #062c33;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-warning hr {
        border-top-color: #ffe8a1;
    }

    .alert-warning .alert-link {
        color: #533f03;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-danger hr {
        border-top-color: #f1b0b7;
    }

    .alert-danger .alert-link {
        color: #491217;
    }

    .alert-light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }

    .alert-light hr {
        border-top-color: #ececf6;
    }

    .alert-light .alert-link {
        color: #686868;
    }

    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }

    .alert-dark hr {
        border-top-color: #b9bbbe;
    }

    .alert-dark .alert-link {
        color: #040505;
    }

    .close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: 0.5;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
        text-decoration: none;
    }

    .close:not(:disabled):not(.disabled):focus,
    .close:not(:disabled):not(.disabled):hover {
        opacity: 0.75;
    }

    button.close {
        padding: 0;
        background-color: transparent;
        border: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    a.close.disabled {
        pointer-events: none;
    }
</style>
</body>