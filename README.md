# Each and every byway

## What's this?
I ride motorbikes.

I ride motorbikes a lot.

But my memory isn't perfect.

And sometimes I get a little down.

So I'd like to look back and see where I've been on my bikes.

But not in my car. Yawn.

It might also be nice to have GPS trackers on my bikes, for security.

Oh, and my partner worries about me, so if they can see I'm still moving that'd be good too.

Oh, and I need to freshen up on some programming. So even if there's something out there to do this already, I want to build it myself. For the lols.

So I want to brush up on Laravel - check out what's happened in the last couple of years. And figure out a sensible front-end too.

Oh and I want to get it done this weekend.

## Three, two, one... Go!
I'll need to write an API that will receive pings from a GPS tracker. I want the tracker to be zero-touch. I told you I've got a bad memory - I don't want to have to remember to turn it on, or off. It should do it's thing without any intervention from me. I'm imaging a GPS module wired into the bike, with either a sim card or bluetooth/wifi connection to my phone. Sim would be the most hands-off approach, but costly, unless I can use my phone's data via bluetooth... although it wouldn't then work as a security tracker. Hmm, some options to consider later. For now I'll just assume there's a feasible solution out there and use an app on my mobile phone.

## Let's throw together a quick prototype
1. Receive coordinates from an app on my phone, and store it in a database
   * I'll need a live site. I've used Heroku before and it abstracts a lot of the devops away, so let's go with that.
   * I can use git to push my code to the Heroku site.
   * And I can get away with a temporary url for now.
   * I'm gonna need an app on my phone to send the data. Pretty sure there's something out there for this...
   * Wow, that was way more difficult than I thought. I'm gonna have to write my own app if I want to do this properly.
   * But for now, I'm using GPSLogger. Wnly available through F-Droid, which is like Google Play but unsigned. Seems legit though.
   * It's the only app I could find that will ping a custom url with my position. And it's open source. Thanks GPSLogger.
   * After a lot of 'fixing it in prod', I've finally got my endpoint working. GPSLogger sends a request with GET params of my latitude, longitude, and some other bits of geo data. My endpoint stores all that in a model I've called 'Pings'.
2. Now I've got some pings, let's show them on a map.
   * After a bit of searching, a js library called 'Leaflet' looks perfect.
   * I've decided to try out Livewire for the front-end. So a bit of learning here to configure everything.
   * But I've got the map up, and I'm displaying cute little markers for all my pings. Scratch that, I've changed the markers to a line.
   * Huh... If I ever show this to anyone they'll see exactly where I live. Let me just add some randomness to any markers that are within a certain distance of my house.
   * Cool, now with enough pings there'll eventually be a circle of markers perfectly centered on... my... house...
   * Ok, rather than specifying my home as the center of my 'home area', I've used a slightly different location nearby. That'll be fine for now. Later on I want to revisit this.
   * Oh, and now I'm disregarding any pings that are less than a certain distance from the last ping. No point taking up DB space with those.
   * I think this is working well enough for a test...
3. Ride
   * Don't you just hate having to QA your own code?
   * A couple of hours on the bike ought to give it a good test. For science. Laters
   * ... there was a bug, the api was erroring out. I've got it fixed, but now I'm gonna have to go for another ride. Damn, damn, damn.

## I'm starting to get some ideas
That's about it for this weekend. I've had a lot of fun and learnt some neat new tricks. Model accessors and mutators are much prettier now! Heroku has been great to work with. And it's so nice to get back into PHPStorm and find my fingers tapping away at all my old shortcuts.

But ideas! Yes. I'm getting grandiose ideas about opening this up to other riders. I'll have a chat with some and see if the concept is of any interest. 

In the meantime, I'll focus on my target market - me.

I want to see all my rides on the map, but also when I took them. I'm guessing rather than a single line of all my pings, I could split the pings up by day. Oh and to accommodate longer trips - where the start point isn't my home area. Although I'll have to test out how many pings the map is happy to show at once. I might need to think about caching the lines somehow... They're already simple vectors, but at some point a bitmap image is going to be lighter weight. I could overlay that on the map, and move it around as the map moves, and zooms... maybe I'll leave that for another weekend!

Oh and I'm sure I'll want to be able to delete certain rides. Or rather times I forgot to stop the tracking app.

And of course I want all this to be done with zero-touch from me. So maybe the next step is to look into the hardware.


## TODO
* Route smoothing
* Encrypt lat & lon 
* ~~Select single journey~~
* Select day of journeys
* Export to gpx
* Import from gpx
