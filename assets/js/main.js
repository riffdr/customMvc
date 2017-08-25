var host,
    listStructureJson = { // To be used as a reference to hierarchy of the JSON Data from BetVictor's API
    sports :{
        childClass: "events",
        grandchildClass: "outcomes"
    },
    events :{
        childClass: "outcomes"
    },
    outcomes : {
        childClass: null
    }
};

$(document).ready(function () {
    setLanguageListener();
    host = location.href.split('/')[2];
    var sportsList = new List( 'sports'); // Populates the first list.
    sportsList.init();
});

// Listener for language support
function setLanguageListener(){
    $("#country-selector").change(function(){
        var redirectUrl = '//' + host + "/" ;
        redirectUrl += $(this).val() != "en" ? $(this).val() : "";
        window.location = redirectUrl;
    });
}

// List Class - Populates a list given a listItem Type (sports, events, outcomes). May take optional parameters parentId and grandParentId referring to its parent(s) listItem id(s)
function List(listItemType, parentId, grandParentId ){

    var self = this;
    this.apiListData = "";
    this.init = function(){
        self.getApiData(function(response) {
            self.apiListData = JSON.parse(response);
            self.renderTreeMenu();
        });
    };
    // Returns the apiListData from the app's API
    this.getApiData = function(callback){
        var url = '//' + host + self.getApiUrl();
        var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        xobj.open('GET',url, true);
        xobj.onreadystatechange = function () {
            if (xobj.readyState == 4 && xobj.status == "200") {
                // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
                callback(xobj.responseText);
            }
        };
        xobj.send(null);
    };
    // Determines the corresponding API Url depending of the listItemType
    this.getApiUrl = function(){
        switch (listItemType){
            case "sports":
                return "/sports/";
                break;
            case "events":
                return "/sports/"+ parentId + "/";
                break;
            case "outcomes":
                return "/sports/"+ grandParentId + "/events/" + parentId + "/";
                break;
        };
    };

    // Processes the API data into <li> elements that populate their matching parent <ul>
    this.renderTreeMenu = function() {
        var listItemHtml = "";
        if(self.apiListData.length != 0){
            $.each(self.apiListData,function(index, value){
                var innerHtml = value.title ? value.title : value.description;
                listItemHtml += "<li class='listItem-"+ listItemType + "' data-itemId='"+ value.id + "' data-type='"+ listItemType + "' >" + innerHtml + "</li>" ;
            });
        } else{
            var innerHtml = translationLibrary.no + " " + translationLibrary[listItemType];
            listItemHtml += "<li class='listItem-"+ listItemType + "'>" + innerHtml + "</li>" ;
        }

        var containerClass = ".list-" + listItemType;
        $(containerClass).html(listItemHtml);
        this.setListItemListeners();
    };

    // Sets the click listeners for all the list items whose id deepens in the API  listStructureJson
    this.setListItemListeners = function(){
        if(listStructureJson[listItemType].childClass){ // Only if the list item type has any children (events or outcomes)
            var listItemClass = ".listItem-"+ listItemType ; // Iterate through all  <li> elements matching the class  and assign to them an onClick listener
            $(listItemClass).each(function(){
                $(this).click(function(){ //
                    var childContainerClass = ".list-" + listStructureJson[listItemType].childClass;
                    $(childContainerClass).empty(); // Empties the child list update
                    if( listStructureJson[listItemType].grandchildClass){ // Empty if the grandChild list while the child list loads
                        var grandChildContainerClass = ".list-" + listStructureJson[listItemType].grandchildClass;
                        $(grandChildContainerClass).empty();
                    }
                    var list = parentId ? new List(listStructureJson[listItemType].childClass,  $(this).data("itemid"), parentId):  new List(listStructureJson[listItemType].childClass,  $(this).data("itemid"));
                    list.init();
                });
            });
            $(listItemClass)[0].click(); // Will generate a new List object for the children of the list item
        }
    };
}