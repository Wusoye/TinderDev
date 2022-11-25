const lstAddFriend = Array.from(document.getElementsByClassName('addFriend'))
const lstDelFriend = Array.from(document.getElementsByClassName('delFriend'))

class Fetch {
    static async get(url) {
        const response = await fetch(url)
        const data = await response.json()
        return JSON.parse(data)
    }
}


lstAddFriend.forEach(el => {
    el.addEventListener('click', async (evt) => {
        const lstid = el.getAttribute('data-id').split('/')
        const idOwner = lstid[0]
        const idFriend = lstid[1]

        const result = await Fetch.get('http://localhost/TinderDev/public/api/add_friend/'+idOwner+'/'+idFriend)

        console.log(result)

        const imgNoFav = document.getElementById('img-no-fav-'+idFriend)
        const imgFav = document.getElementById('img-fav-'+idFriend)

        if (result) {
            if (!result['estAmi']) {
                imgNoFav.style.setProperty('display', 'none')
                imgFav.style.setProperty('display', null)
                imgFav.style.setProperty('cursor', 'pointer')
            } else {
                imgNoFav.style.setProperty('display', null)
                imgFav.style.setProperty('display', 'none')
                imgFav.style.setProperty('cursor', 'pointer')
            }
        }
    })
})

/*
btnAddFav.forEach(el => {
    el.addEventListener('click', async (evt) => {
        const lstid = el.getAttribute('data-id').split('/')
        const idOwner = lstid[0]
        const idFriend = lstid[1]

        const result = await Fetch.get('http://localhost/TinderDev/public/api/add_friend/'+idOwner+'/'+idFriend)


        const estOk = result['result']
        if (estOk) {
            const estFavorie = result['value']['estFavorie']
            const idRecette = result['value']['idRecette']
            const imgNoFav = document.getElementById('img-no-fav-'+idRecette)
            const imgFav = document.getElementById('img-fav-'+idRecette)
            if (estFavorie) {
                imgNoFav.style.setProperty('display', 'none')
                imgFav.style.setProperty('display', null)
                el.childNodes[0].nodeValue = 'Retirer des favoris'
            } else {
                imgNoFav.style.setProperty('display', null)
                imgFav.style.setProperty('display', 'none')
                el.childNodes[0].nodeValue = 'Ajouter aux favoris'
            }
        }
    })
})
*/

console.log(lstAddFriend)