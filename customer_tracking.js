



export class Recommender {
    keywords = {};

    //we should change this to something reasonable later, for now its fine
    timeWindow = 10000000;
    constructor() {
        this.load();
    }

    save() {
        localStorage.recommenderKeywords = JSON.stringify(this.keywords);
    }

    load() {
        if (localStorage.recommenderKeywords === undefined) {
            this.keywords = {};
        } else {
            this.keywords = JSON.parse(localStorage.recommenderKeywords);
        }
        this.deleteOldKeywords();
    }

    addKeyword(word) {
        if (this.keywords[word] === undefined) {
            this.keywords[word] = {count : 1, date : new Date().getTime()};
        } else {
            this.keywords[word].count++;
            this.keywords[word].date = new Date().getTime();
        }
        this.save();
    }

    deleteOldKeywords() {
        let currTime = new Date().getTime();
        for (let word in this.keywords) {
            if (currTime - this.keywords[word].date > this.timeWindow) {
                delete this.keywords[word];
            }
        }
        this.save();
    }
    
    getTop3Keywords() {
        let maxCount = [0,0,0];
        let maxKeywords = ["","",""];
        for (let word in this.keywords) {
            if (this.keywords[word].count > maxCount[2]) {
                maxCount = shiftDown(maxCount, 2, this.keywords[word].count);
                maxKeywords = shiftDown(maxKeywords, 2, word);
            } else if (this.keywords[word].count > maxCount[1]) {
                maxCount = shiftDown(maxCount, 1, this.keywords[word].count);
                maxKeywords = shiftDown(maxKeywords, 1, word);
            } else if (this.keywords[word].count > maxCount[0]) {
                maxCount[0] = this.keywords[word].count;
                maxKeywords[0] = word;
            }
        }
        return maxKeywords;
    }
}

function shiftDown(arr, pos, value) {
    for (let i = 0; i < pos; i++) {
        arr[i] = arr[i + 1]
    }
    arr[pos] = value;
    return arr;
}