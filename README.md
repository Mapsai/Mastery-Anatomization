# Mastery-Anatomization
Mastery anatomization (retriever) from Riot Games API

Mastery Anatomization can retrieve data about masteries from Riot Games API  for either 1 or 2 (comparision) Summoners. It also includes a small quiz. There are 10 question in this quiz and all of them related to Mastery system. Only 1 answer can be correct in any question. After choosing you will see incorrect/correct answer(s). Information was taken from LoL wikipedia and Riot Support site.

Retrieving data from 1 Summoner:
- Fields for username and select options for server are provided (and required);
- Additionally, there are 3 checkboxes to configure a little bit displaying results;
- With a help of Ajax and JavaScript, server returns the results and displays below the main element with inputs.

Retrieving data from 2 Summoners (comparing):
- In addition, there are additional 2 new inputs with same type (for Summoner #2).
- There are 2 checkboxes instead of 3 because 1 is not valid for comparision).
- As with previous one, results are being displayed with a help of Ajax and JS. In table format the overall structure is a little different (so that user could easier to compare). Additionally, total score or level is provided.

Websites uses localStorage (but not cookies) to store information on local user's machine. Cache is used for images, javascript files and icons.
