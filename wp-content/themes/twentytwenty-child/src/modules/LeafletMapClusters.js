import L from "leaflet";
import { MarkerClusterGroup } from "leaflet.markercluster";
import axios from "axios"

class LeafletMap {
    constructor() {
        document.querySelectorAll(".leaflet-map").forEach(el => {
            el.post_type = !('template' in el.dataset) ? 'post' : el.getAttribute("data-template") //template keeps the post-type
            el.post_id = !('id' in el.dataset) ? '' : el.getAttribute("data-id")
            this.new_map(el)
        })
    }

    new_map(el) {
        let get_url = `${php_to_js.data.root_url}/wp-json/demo/v1/maps?type=${el.post_type}${el.post_id ? `&id=${el.post_id}` : ''}`
        let that = this
        let arrayOfLatLngs = []
        let showLink = false
        const args = {
            zoom: 14,
            centerLat: 0.0,
            centerLng: 0.0,
        }

        axios.get(get_url)
            .then(function (response) {
                // handle success
                //console.log(response);
                if (Array.isArray(response.data)) {

                    const markers = L.markerClusterGroup();

                    const map = L.map(el).setView([args.centerLng, args.centerLng], args.zoom);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map)

                    if (response.data.length > 1) {
                        showLink = true
                    } else {
                        showLink = false
                    }
                    response.data.forEach(element => {
                        if (element.location.latitude !== '' && element.location.longitude !== '') {
                            arrayOfLatLngs.push([element.location.latitude, element.location.longitude])
                            that.add_marker(element, markers, showLink)
                        }
                    });
                    map.addLayer(markers);
                    // center map
                    that.center_map(map, arrayOfLatLngs)

                }


            })
            .catch(function (error) {
                // handle error
                //Show a custom error Map
                //Console error
                console.log(error);
            })

    } // end new_map

    add_marker(element, markers, showLink) {

        const templateUrl = php_to_js.data.theme_uri

        const myIcon = L.icon({
            iconUrl: templateUrl + "/images/maps/icon.png",
            iconRetinaUrl: templateUrl + '/images/maps/icon-retina.png',
        })

        const { latitude, longitude, address } = element.location

        const marker = new L.Marker([latitude, longitude], { icon: myIcon }).bindPopup(`
        ${`<p class="popup-title">${showLink ? `<a href="${element.link}">` : ''}${element.title.rendered}${showLink ? '</a>' : ''}</p>`}
        ${address ? `<p class="popup-address">${address}</p>` : ''}        
    `)

        //marker.addTo(map)

        markers.addLayer(marker);


    } // end add_marker

    center_map(map, arrayOfLatLngs) {
        var bounds = new L.LatLngBounds(arrayOfLatLngs);

        // only 1 marker?
        if (arrayOfLatLngs.length == 1) {
            // set center of map
            map.setView(arrayOfLatLngs[0], 16, { animation: true });
        } else {
            // fit to bounds
            map.fitBounds(bounds);
        }
    } // end center_map

}

export default LeafletMap