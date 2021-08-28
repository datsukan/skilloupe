<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center" style="max-width: initial">
            <v-col cols="12" sm="10" md="8">
                <p
                    class="grey--text text--darken-3 display-1 font-weight-bold mb-7"
                    v-text="'Oops!'"
                ></p>
                <p
                    class="red--text lighten-1 headline font-weight-medium mb-3"
                    v-text="'500 Internal Server Error'"
                ></p>
                <p
                    class="grey--text text--darken-3"
                    v-text="'正常に処理されませんでした。\n改善されない場合は管理者までお問合せください。'"
                ></p>
            </v-col>
        </v-row>
    </v-container>
</template>
