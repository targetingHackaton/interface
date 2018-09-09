Targeting Hackathon interface
============

# Short information
- This project is made in Symfony and is an interface (GUI) for a GoLang webserver.
- The project connects to GoLang webserver to get recommendations for different scenarios
- The GoLang webserver responds with product ids
- The project uses MongoDB to get data about the product ids received (ex: name, image url, etc)

# Routes
## Generic
- /
    - redirects to /listing
- /listing
    - listing of scenarios available and other useful links (like Settings)
- /settings
    - you can change the Showroom or Camera from here
- /emulator
    - you can emulate RPI (Raspberry PI) + Camera HTTP calls to API
## Scenario routes (each will display recommendations based on something)
- /all
    - display recommendations based on people in the room (age and gender)
- /person
    - display recommendations based on entered email (customer)
- /camera
    - display recommendations based on who is in front of camera (age and gender)
