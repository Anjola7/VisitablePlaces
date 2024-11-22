var simplemaps_countrymap_mapdata = {
  main_settings: {
    width: "responsive",
    background_color: "#FFFFFF",
    background_transparent: "yes",
    border_color: "#ffffff",
    state_description: "State description",
    state_color: "#88A4BC",
    state_hover_color: "#3B729F",
    state_url: "",
    border_size: 1.5,
    all_states_inactive: "no",
    all_states_zoomable: "yes",
    location_description: "Location description",
    location_url: "",
    location_color: "#FF0067",
    location_opacity: 0.8,
    location_hover_opacity: 1,
    location_size: 25,
    location_type: "square",
    location_image_source: "frog.png",
    location_border_color: "#FFFFFF",
    location_border: 2,
    location_hover_border: 2.5,
    all_locations_inactive: "no",
    all_locations_hidden: "no",
    label_color: "#ffffff",
    label_hover_color: "#ffffff",
    label_size: 16,
    label_font: "Arial",
    label_display: "auto",
    label_scale: "yes",
    hide_labels: "no",
    hide_eastern_labels: "no",
    zoom: "yes",
    manual_zoom: "yes",
    back_image: "no",
    initial_back: "no",
    initial_zoom: "-1",
    initial_zoom_solo: "no",
    region_opacity: 1,
    region_hover_opacity: 0.6,
    zoom_out_incrementally: "yes",
    zoom_percentage: 0.99,
    zoom_time: 0.5,
    popup_color: "white",
    popup_opacity: 0.9,
    popup_shadow: 1,
    popup_corners: 5,
    popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
    popup_nocss: "no",
    div: "map",
    auto_load: "yes",
    url_new_tab: "no",
    images_directory: "default",
    fade_time: 0.1,
    link_text: "View Website",
    popups: "detect",
    state_image_url: "",
    state_image_position: "",
    location_image_url: ""
  },
  state_specific: {
    AL01: {
      name: "Berat",
      url: "berat.php"
    },
    AL02: {
      name: "Durrës",
      url: "durres.php"
    },
    AL03: {
      name: "Elbasan",
      url: "elbasan.php"
    },
    AL04: {
      name: "Fier",
      url: "fier.php"
    },
    AL05: {
      name: "Gjirokastër",
      url: "gjirokaster.php"
    },
    AL06: {
      name: "Korçë",
      url: "korce.php"
    },
    AL07: {
      name: "Kukës",
      url: "kukes.php"
    },
    AL08: {
      name: "Lezhë",
      url: "lezhe.php"
    },
    AL09: {
      name: "Dibër",
      url: "diber.php"
    },
    AL10: {
      name: "Shkodër",
      url: "shkoder.php"
    },
    AL11: {
      name: "Tiranë",
      url: "tirane.php"
    },
    AL12: {
      name: "Vlorë",
      url: "vlore.php"
    }
  },
  locations: {
    "0": {
      name: "Tirana",
      lat: "41.3275",
      lng: "19.818889"
    },
    "1": {
      lat: "39.8680",
      lng: "20.0060",
      name: "Saranda"
    }
  },
  labels: {
    AL01: {
      name: "Berat",
      parent_id: "AL01"
    },
    AL02: {
      name: "Durrës",
      parent_id: "AL02"
    },
    AL03: {
      name: "Elbasan",
      parent_id: "AL03"
    },
    AL04: {
      name: "Fier",
      parent_id: "AL04"
    },
    AL05: {
      name: "Gjirokastër",
      parent_id: "AL05"
    },
    AL06: {
      name: "Korçë",
      parent_id: "AL06"
    },
    AL07: {
      name: "Kukës",
      parent_id: "AL07"
    },
    AL08: {
      name: "Lezhë",
      parent_id: "AL08"
    },
    AL09: {
      name: "Dibër",
      parent_id: "AL09"
    },
    AL10: {
      name: "Shkodër",
      parent_id: "AL10"
    },
    AL11: {
      name: "Tiranë",
      parent_id: "AL11"
    },
    AL12: {
      name: "Vlorë",
      parent_id: "AL12"
    }
  },
  legend: {
    entries: []
  },
  regions: {}
};

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.simplemaps-countrymap-state').forEach(function(element) {
    element.addEventListener('click', function() {
      var stateId = this.getAttribute('data-id'); // Merr ID-në e qytetit të klikuar
      var url = simplemaps_countrymap_mapdata.state_specific[stateId] ? simplemaps_countrymap_mapdata.state_specific[stateId].url : "";
      if (url) {
        window.location.href = url; // Ridrejto tek URL-ja e caktuar për qytetin e klikuar
      }
    });
  });
});
