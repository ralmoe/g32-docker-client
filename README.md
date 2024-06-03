# G32 dockerized client

The [OttoWilde G32 Connected](https://www.ottowildegrillers.com/products/g32-connected-gasgrill?variant=39705156092066) 
has 4 internal temperature-sensors and 4 plug-in thermometers that send their data to the Otto Wilde Cloud. The
companion-app can pull this data and visualize it.

This docker-compose-application pulls the data from the Otto Wilde Cloud and saves it to a csv-file.

## Thanks to

* [fschwarz86](https://github.com/fschwarz86) reverse engineered the Otto Wilde api and how to setup the 
socket-connection
* [Sanderson](https://www.grillsportverein.de/forum/members/sandorson.162581/) discovered how to interpret the the 
data received from the socket-connection

## Preconditions

* The G32 needs an active internet-connection wlan
* existing account for the otto-wilde-app
  * [Apple](https://apps.apple.com/de/app/otto-wilde-app/id1515540095)
  * [Android](https://play.google.com/store/apps/details?id=com.otto.application&hl=de&gl=US&pli=1)
* serial-number of the G32
  * find the number printed on a plate on the back of your G32 (not the sticker in your grease-drawer!)
* your pop (See next chapter)

## The `pop`-Problem
The socket connection needs a hash called `pop` to succesfully authorize the connection. Tracing traffic
from the mobile-app to Otto Wilde unveils this hash. Currently there is no other known way to get it.

## Usage
* rename `.env.dist` to `.env` and add 
  * app-credentials
    * username
    * password
  * G32 serial-number 
  * G32 pop
  * path to output-directory
* start `docker compose up -d`

## Output-format
By default the file is stored in `/data/temperatures.csv` and looks like this:

```
1717409973;22;22;22;22;;;;
1717409975;22;22;22;22;;;;
1717409977;22;22;22;22;;;;
1717409979;22;22;22;22;;;;
1717409981;22;22;22;22;;;;
```

The grill is off, so the 4 grill zone sensors just show current the outside temperature. The thermometers are not plugged in, so the 
last four columns are empty

## Unhandled issues
If the grill is turned off, no data is received. The same happens, if the serial-number is wrong. 
This can be misleading.

## Possible usages
* make the output-file available outside of the container and use it e.g. in [Home Assistant](https://www.home-assistant.io/)

## Roadmap
* get the filling level of the gas bottle
* get the opening state of the hood (not sure if this data is send)
* find a way to avoid using the Otto Wilde cloud by receiving the data directly from the grill.
