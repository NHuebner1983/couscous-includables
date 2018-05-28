# Couscous Extended

Two features have been added.

 - Ability to include MD files within other MD files.
 - Ability to use constants.

# Including MD files within MD files

Couscous documentation engine but with some small tweaks allowing you to use includes of other MD files within MD files. Since the commit already has the compiled PHAR file for your compiler, all you need to do is add your content and use the new include tags in your MD files. This allows you to build a sisngle MD file for a section and include multiple MD files into that single MD file. The original source was edited to look for these include tags. See next section on how to use include tags.

# Example of Include within MD file

Open the file: `compiler/markdown/api/api.md`

```
---
currentMenu: api
---
# API Documentation

Welcome to the API Documentation Page

[include('includes/description/get-subscribers.mdd')]
[include('includes/request/get-subscribers.mdd')]
[include('includes/response/get-subscribers.mdd')]
```

# Using Constants in your MD files

If you have repetitive content, you can take advantage of the other feature added which allows you to create constants.

# Creating a Constants File

Open the file: `compiler/markdown/api/constants.mdd`

*Note*: since there are no escaping characters, it's advised that your entire content for a single constant goes on 1 single line (length does not matter), but it is delimited by `chr(10)` return when parsing constants for available use.
```
@Yes = Yes
@No = No
@Optional = Optional
@int = Integer
@string = String

@RemindAPIKey = Don't have an API Key yet? [Click here to get one.](http://somewebsite.com/sellers?get-api-key=1)

@DataSend = Data to send in your request:
@DataReceive = Data you will receive in the response:

@APIKey = [API Key](http://somewebsite.com/sellers?get-api-key=1)
@APIKeyDescription = Your Subscription API Key.

@AccountID = Subscription ID
@AccountIDDescription  = The Subscription's unique ID your subscribers are subscribed to.

@CourseID = Subscriber ID
@CourseIDDescription = The Subscriber's unique ID.
```

# How to use compiler/markdown/api/constants.mdd

Open the file: `compiler/markdown/api/includes/description/get-subscribers.mdd`

```
### Get Subscribers

To retrieve a list of subscribers you must have the following information available. [@RemindAPIKey]

#### [@DataSend]

| Fields   |      Description    | Required |
|----------|---------------------|:----------:|
| [@APIKey]  |  [@APIKeyDescription] | [@Yes] |
| [@AccountID] | [@AccountIDDescription] | [@Yes] |

##### [@DataReceive]

| Fields   |      Description    | Type |
|----------|---------------------|:------:|
| [@CourseID] | [@CourseIDDescription] | [@int] |
```

As you can see, we can access our constants by using `[@ConstantName]`. When you use constants, it makes it easier in the future to make corrections and quickly write your documentation. It also allows your Q/A or content management team to restructure or change verbiage in sentences without going through all of your final MD files.

# Questions ?

Post an issue. The example provided should be self explanatory. You can also edit the Source if you need to add more functionality.

