function t(_t, search, defVal) {
    if(_t){
        let s = search.split('.')
        if(s.length === 2) {
            if (_t[s[0]]) {
                if (_t[s[0]][s[1]]) {
                    return _t[s[0]][s[1]]
                }
            }
        }else if(s.length === 1){
            if(_t['generic'][s[0]]) {
                return _t['generic'][s[0]]
            }
            return search;
        }
    }
    return defVal;
}

export { t};
