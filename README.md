<h1>SMS Subscription Service</h1>

This project was initially developed for the OCC Programming Club (<a href="https://github.com/occprogramming">repo</a> | <a href="http://occprogramming.club">site</a>), but can be easily ported for other organizations with the appropriate setup. Using **SMS Subscription Service**, members of the club are able to subscribe to a recurring SMS notification that will notify them of our weekly meetings, 60 minutes before the starting time.

The functions of this service include: managing the subscription of a club member (adding them to the database, deleting them, making sure they are already a club member...) and sending a personalized text message to the subscribed and activated members from the database.
  
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

  This program consists of four parts: **Subscription**, **Messaging**, **GoogleAutoToken**, and **SMS Deployer**.

  - The **Subscription** part consists of a front-end and back-end. The front-end manages the different possible errors and communicates to a PHP script that uses the Subscription class, and the back-end consists of this Subscription class. The class is in charge of managing (adding from, deleting from) the spreadsheets of *subscribers*, and reading the spreadsheets of *club members*.
  
  - The **Messaging** part consists of the Messaging class, which is in charge of sending a custom text message to a list of subscribers.
  
  - The **GoogleAutoToken** class controls a series of files and allows the Subscription class to connect to the Google Spreadsheets and query it as a database. The purpose of the GoogleAutoToken class is to automatically manage the OAuth 2.0 connection between the server where this service is hosted and the Google Spreadsheets Service.
  
- The **SMS Deployer** file is the file that the cron job must call at a specified time and day. Its purpose is to get all activated subscribers using the Subscription class, create a list of these subscribers, and pass it to the Messaging class so it can send the text messages.
  
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
