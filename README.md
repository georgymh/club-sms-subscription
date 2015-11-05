<h1>SMS Subscription Service</h1>

This project consists of an SMS Subscription Service intended to be used by the OCC Programming Club (<a href="https://github.com/occprogramming">repo</a> | <a href="https://occprogramming.club">site</a>). Using this service, members of the club are able to subscribe to a recurring SMS notification that will notify them of our weekly meetings, 60 minutes before they start.
  
<h2>The Why</h2>

In order to prepare for upcoming hackathons, our club decided to make use of meeting time to learn how to successfully implement third-party APIs. With this goal in mind, we wanted to make something useful for both ourselves and future club members, so we decided to face the challenge of (chronically) late members by taking away their excuses to arrive late to the meetings.
  
<h2>Current Features</h2>

  <ul>
    <li>Subscribes and unsubscribes a member</li>
    <li>Checks if the user is a member of the club before subscribing/unsubscribing</li>
    <li>Checks validity of input phone numbers, and also cleans them</li>
    <li>Sends SMS to activated subscribers with only one file execution</li>
    <li>Manages Google OAuth 2.0 sessions automatically</li>
  </ul>
  
<h2>How It Works</h2>

  Operated entirely by magic and fueled with the blood, sweat, and tears of late club members.
  
  Unconvinced? <i> We will soon write the exact inner workings of our system. Stay tuned.</i>

<h2>Technologies Used</h2>

  <ul>
    <li><a href="https://www.twilio.com">Twilio API</a> - To send SMS</li>
    <li><a href="https://developers.google.com/drive/">Google Drive API</a> - To store the subscriber's information</li>
    <li><a href="https://github.com/asimlqt/php-google-spreadsheet-client">PHP Google Spreadsheet Client</a> - To easily query our Google Sheets</li>
    <li><a href="https://github.com/google/google-api-php-client">Google API PHP Client</a> - To refresh our tokens</li>
    <li><a href="https://github.com/Respect/Validation">Respect/Validation</a> - To clean our data</li>
  </ul>
  
<h2>Authors</h2>

<a href="https://github.com/georgymh/">Georgy Marrero</a>

<a href="https://github.com/bfullam/">Bryan Fullam</a>
