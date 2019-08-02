var map;
var videoDateTime;
var data;

var caricon;
var polyline;
var polylinePoints;

function init() {

    var xhr = new XMLHttpRequest();
    xhr.open('GET', "/trip/getData/?id=" + ID, true);
    xhr.responseType = 'json';
    xhr.onload = function () {
        var status = xhr.status;
        if (status === 200) {
            json = xhr.response;
            data = json;
            videoDateTime = Object.keys(json)[0];
            updateData();
        }
        };
        xhr.send();


}

function updateData(){
    map = new L.Map('map');
    L.tileLayer('http://{s}.tile.openstreetmap.com/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);
    map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

    //Define an array of Latlng objects (points along the line)
    polylinePoints = [];

    Object.keys(data).forEach(function(k){
        if(data[k]["Lon"] != "0" && data[k]["Lon"] != "0") {
            polylinePoints.push(new L.LatLng(data[k]["Lon"], data[k]["Lat"]));
        }
    });

    var polylineOptions = {
        color: 'blue',
        weight: 6,
        opacity: 0.5
    };

    polyline = new L.Polyline(polylinePoints, polylineOptions);

    map.addLayer(polyline);

    if(polylinePoints.length > 0) {
        // zoom the map to the polyline
        map.fitBounds(polyline.getBounds());
    }

    var car = L.icon({
        iconUrl: '/views/image/car.png',

        iconSize: [32, 32], // size of the icon
        iconAnchor: [17, 17], // point of the icon which will correspond to marker's location
        popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    });
    caricon = L.marker([0, 0], {icon: car}).addTo(map);

    var tmp = [];
    Object.keys(data).forEach(function(k){
        tmp.push(data[k]["Speed"]);
    });
    new Chartist.Line('.speed-chart', {
        series: [tmp]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        height: '250px',
        showLabel: false,
        axisX: {
            showGrid: false,
            showLabel: false,
            offset: 0
        }, showLine: false
    });

    tmp = [];
    Object.keys(data).forEach(function(k){
        tmp.push(data[k]["RPM"]);
    });
    new Chartist.Line('.RPM-chart', {
        series: [tmp]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        height: '250px',
        showLabel: false,
        axisX: {
            showGrid: false,
            showLabel: false,
            offset: 0
        }, showLine: false
    });

    tmp = [];
    Object.keys(data).forEach(function(k){
        tmp.push(data[k]["CTemp"]);
    });
    new Chartist.Line('.coolant-chart', {
        series: [tmp]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        height: '250px',
        showLabel: false,
        axisX: {
            showGrid: false,
            showLabel: false,
            offset: 0
        }, showLine: false
    });

    tmp = [];
    Object.keys(data).forEach(function(k){
        tmp.push(data[k]["ATemp"]);
    });
    new Chartist.Line('.air-chart', {
        series: [tmp]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        height: '250px',
        showLabel: false,
        axisX: {
            showGrid: false,
            showLabel: false,
            offset: 0
        }, showLine: false
    });

    window.setInterval(function () {
        update();
    }, 500);

    var date1 = new Date(data[Object.keys(data)[0]]["Timestamp"]).getTime();
    var date2 = new Date(data[Object.keys(data)[Object.keys(data).length - 1]]["Timestamp"]).getTime();

    var delta = Math.abs( date1 - date2) / 1000;
    var hours = Math.floor(delta / 3600) % 24;
    delta -= hours * 3600;
    var minutes = Math.floor(delta / 60) % 60;
    delta -= minutes * 60;
    var seconds = delta % 60;

    document.getElementById("tripTime").innerHTML = hours.pad(2)+":"+minutes.pad(2)+":"+seconds.pad(2);

    totalSpeed = 0;
    Object.keys(data).forEach(function(k){
        totalSpeed += parseInt(data[k]["Speed"]);
    });
    avg = (totalSpeed/Object.keys(data).length);
    document.getElementById("avgSpeed").innerHTML = Math.round(avg * 100) / 100;

    topSpeed = 0;
    Object.keys(data).forEach(function(k){
        if(parseInt(data[k]["Speed"]) > topSpeed){
            topSpeed = parseInt(data[k]["Speed"]);
        }
    });
    document.getElementById("topSpeed").innerHTML = topSpeed;

    topRPM = 0;
    Object.keys(data).forEach(function(k){
        if(parseInt(data[k]["RPM"]) > topRPM){
            topRPM = parseInt(data[k]["RPM"]);
        }
    });
    document.getElementById("topRPM").innerHTML = topRPM;
    document.getElementById("tripDistance").innerHTML = data[Object.keys(data)[0]]["Distance"];

    geocode(data[Object.keys(data)[10]]["Lon"], data[Object.keys(data)[10]]["Lat"], document.getElementById("startLoc"));
    geocode(data[Object.keys(data)[Object.keys(data).length - 1]]["Lon"], data[Object.keys(data)[Object.keys(data).length - 1]]["Lat"], document.getElementById("endLoc"));
}

function geocode(lon, lat, elem){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', "https://nominatim.openstreetmap.org/reverse?format=json&lat="+lon+"&lon="+lat+"&zoom=18&addressdetails=1", true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        var status = xhr.status;
        if (status === 200) {
            json = xhr.response;
            if(!json.error) {
                dis = json.display_name;
                dis = dis.substring(0, dis.lastIndexOf(','));
                dis = dis.substring(0, dis.lastIndexOf(','));
                dis = dis.substring(0, dis.lastIndexOf(','));
                elem.innerHTML = dis.substring(0, dis.lastIndexOf(','));
            }else{
                elem.innerHTML = "Could not determine location"
            }
        }
    };
    xhr.send();
}
 
function update(){
    var video = document.getElementById("video");
    var time = parseInt(videoDateTime) + Math.floor(video.currentTime);

    if(polylinePoints.length > 0) {
        if(document.getElementById("followCar").checked) {
            map.panTo(new L.LatLng(data[time]["Lon"], data[time]["Lat"]));
        }
        caricon.setLatLng([data[time]["Lon"], data[time]["Lat"]]);
    }

    var gears = {"0": 0,"0.008": 1, "0.009": 1, "0.012": 2, "0.013": 2, "0.014": 2, "0.015": 2, "0.019": 3, "0.020": 3, "0.021": 3, "0.022": 3, "0.025": 4, "0.026": 4, "0.027": 4, "0.028": 4, "0.032": 5, "0.033": 5, "0.034": 5, "0.035": 5};
    gear = 0;
    if(data[time] != null){
        gear = gears[toFixed(parseFloat(data[time]["Speed"]/data[time]["RPM"]),3)+""];
    }

    document.getElementById("Speed").innerHTML = data[time]["Speed"] + " Km/h";
    drawGraph(time,document.getElementById("Speed").parentElement,"Speed");

    document.getElementById("RPM").innerHTML = data[time]["RPM"] + " Rpm";
    drawGraph(time,document.getElementById("RPM").parentElement,"RPM");

    document.getElementById("Gear").innerHTML = gear;

    document.getElementById("Time").innerHTML = data[time]["Timestamp"].split(" ")[1];

    document.getElementById("CTemp").innerHTML = "Coolant " + data[time]["CTemp"] + " &deg;C";
    drawGraph(time,document.getElementById("CTemp").parentElement,"CTemp");

    document.getElementById("ATemp").innerHTML = "Intake " + data[time]["ATemp"] + " &deg;C";
    drawGraph(time,document.getElementById("ATemp").parentElement,"ATemp");

    document.getElementById("Throttle").innerHTML = "Throttle " + data[time]["Throttle"] + " %";
    drawGraph(time,document.getElementById("Throttle").parentElement,"Throttle");
}

function toFixed(num, fixed) {
    var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
    return num.toString().match(re)[0];
}

function changeTab(elem,tab){
    var tabs = document.getElementsByClassName("content");
    Array.from(tabs).forEach(function(element) {
        element.style.display = "none";
    });

    var menuitems = document.getElementsByClassName("topMenuItem");
    Array.from(menuitems).forEach(function(element) {
        element.classList.remove("topMenuItemSelected");
    });

    document.getElementsByClassName(tab)[0].style.display = "";
    elem.classList.add("topMenuItemSelected");
    if(polylinePoints.length > 0) {
        map.invalidateSize();
        map.fitBounds(polyline.getBounds());
    }
}


//Graph

function drawLine(sourceX,sourceY,destnationX,destnationY,context){
  context.beginPath();
  context.moveTo(sourceX, sourceY);
  context.lineTo(destnationX, destnationY);
  context.stroke();
}

function calcScale(data,boxSize,width){
  var result = new Object();
  result.stepInPixel = width / data.length; 


  var min = Number.POSITIVE_INFINITY;
  var max = Number.NEGATIVE_INFINITY;
  for(var i = 0; i < data.length;i++){
    if(data[i] < min){
      min = data[i];
    }
    if(data[i] > max){
      max = data[i];
    }

  }  

  var delta = max - min;

  result.offsetY = min;
  result.multiplicatorY = ((boxSize / delta) / 100) *90;  
  return result; 
}

function drawGraph(time,gauge,field){
    var gdata = [];
    var x = 50;
    while(x >= 0){
        if(data[time-x] != null){
            gdata.push(parseInt(data[time-x][field]));
        }else{
             //fix for issues with missing data in array get nearest working item
             var t = 0;
             while(data[time-x + t] == null){
                t++;
             }
            gdata.push(data[time-x + t][field]);
        }
        x--;
    }
    while(x <= 50){
        if(data[time+x] != null){
            gdata.push(parseInt(data[time+x][field]));
        }else{
             //fix for issues with missing data in array get nearest working item
             var t = 0;
             while(data[time+x +t] == null){
                t--;
             }
            gdata.push(data[time+x + t][field]);
        }
        x++;
    }
    var canvas = document.createElement('canvas');
    canvas.width = gauge.offsetWidth;
    canvas.height = gauge.offsetHeight;
    var context = canvas.getContext('2d');
    context.strokeStyle = "#bdbdbd";
  var boxSize = gauge.offsetHeight;

  var scale = calcScale(gdata,boxSize,gauge.offsetWidth);

  var stepInPixel = scale.stepInPixel;
  var multiplicatorY = scale.multiplicatorY;
  var offsetY = scale.offsetY;

  var offset = 0;
  var lastY = 0;
  for(var i = 0; i < gdata.length;i++){
    var currentY =  ((gdata[i] * multiplicatorY) * -1) + (offsetY* multiplicatorY)  + boxSize ;
    if(i == 0){
      lastY = currentY;
    }

    drawLine(offset,lastY,offset+stepInPixel,currentY,context)
    offset += stepInPixel;
    lastY = currentY;

  } 
   gauge.style.backgroundImage = 'url(' + canvas.toDataURL() + ')';
}

Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
}




